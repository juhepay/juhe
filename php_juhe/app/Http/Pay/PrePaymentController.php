<?php
namespace App\Http\Pay;

use App\Exceptions\CustomServiceException;
use App\Model\FundsTurnoverLog;
use App\Model\Sysconfig;
use App\Model\Tixian;
use App\Model\User;
use App\Requests\EkofapyRequest;
use App\Tool\Md5Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrePaymentController extends Controller
{
    public function index(EkofapyRequest $request)
    {
        $user = User::where(['uid'=>$request->appid,'status'=>1])->first();
        if(!$user) return json_encode(['code'=>0,'msg'=>'商户不存在或未启用'],256);

        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($request->all());
        $sign = $md5Verify->md5Encrypt($prestr,$user->api_key);
        if($sign != $request->sign) return json_encode(['code'=>0,'msg'=>'验签失败'],256);

        // 验证余额
        if($request->amount > $user->balance) return json_encode(['code'=>0,'msg'=>'余额不足'],256);

        $sysconfig = Sysconfig::first();

        if($sysconfig->min_price && $sysconfig->min_price > $request->amount) json_encode(['code'=>0,'msg'=>'最小提现金额：'.$sysconfig->min_price],256);

        // 手续费计算
        if($sysconfig->fl_type == 0)// 单笔
        {
            $fee = $sysconfig->tx_fl;
        }else{ // 比例手续费
            $fee =  $sysconfig->tx_fl ? bcmul($sysconfig->tx_fl/100, $request->amount,2) : 0;
        }

        $params['real_name']= $request->name;
        $params['card_no']  = $request->body;
        $params['bank_name']= $request->bank_name;
        $params['uid']      = $user->uid;
        $params['fee']      = $fee;
        $params['money']    = $request->amount;
        $params['order_no'] = $user->uid.$request->order_no;
        $params['notify_url'] = $request->notify_url;

        DB::connection('mysql')->transaction(function () use ($params) {
            $token = TimeMicroTime();
            redisLock("user:lock:".$params['uid'],$token,10);
            //查询用户原金额
            $user_info = User::where('uid',$params['uid'])->select('balance')->first();
            //账户余额扣除
            $result = User::where('uid',$params['uid'])->where('balance','>=',$params['money'])->decrement('balance',$params['money']);
            unlock("user:lock:".$params['uid'],$token);
            //添加提现信息
            $result && $result = Tixian::create($params);
            if($result)
            {
                // 资金流水
                $data = array(
                    'uid'           => $params['uid'],
                    'amount'        => -$params['money'],
                    'before_balance'=> $user_info->balance,
                    'after_balance' => bcsub($user_info->balance,$params['money'],2),
                    'type'          => 2,
                    'content'       => '申请提现【'.$params['money'].'元】 手续费：'.$params['fee'].'元',
                );
                $result = FundsTurnoverLog::create($data);
            }

            if (!$result) {
                throw new CustomServiceException('提现申请失败');
            }
        }, 1);

        return json_encode(['code'=>1,'msg'=>'申请成功'],256);
    }

    public function query(Request $request)
    {
        if( !isset($request->appid) || !isset($request->sign) || !isset($request->order_no) )
        {
            return json_encode(['code'=>0, 'msg'=>'缺少参数'],256);
        }

        $user = User::where(['uid'=>$request->appid])->select('api_key')->first();
        if(!$user) return json_encode(['code'=>0, 'msg'=>'商户不存在'],256);

        $md5Verify = app(Md5Verify::class);
        $prestr = $md5Verify->getSign($request->all());
        $sign = $md5Verify->md5Encrypt($prestr,$user->api_key);
        if($sign != $request->sign)
        {
            return json_encode(['code'=>0, 'msg'=>'验签失败'],256);
        }

        $tixian = Tixian::where(['uid'=>$request->appid,'order_no'=>$request->appid.$request->order_no])->first();
        if(!$tixian) return json_encode(['code'=>0, 'msg'=>'订单不存在'],256);
        $data = [
            'code'     => 1,
            'msg'      => '查询成功',
            'order_no' => $request->order_no,
            'status'   => $tixian->status,
            'amount'   => $tixian->money,
            'body'     => $tixian->card_no
        ];

        return json_encode($data,256);
    }
}
