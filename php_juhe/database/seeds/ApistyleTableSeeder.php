<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApistyleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apistyles')->insert([
            [
                'api_name'  => '支付宝H5',
                'api_mark'  => 'alipay_h5',
                'status'    => 1
            ],
            [
                'api_name'  => '支付宝扫码',
                'api_mark'  => 'alipay_scan',
                'status'    => 1
            ],
            [
                'api_name'  => '微信扫码',
                'api_mark'  => 'wechat_scan',
                'status'    => 1
            ],
            [
                'api_name'  => '微信H5',
                'api_mark'  => 'wechat_h5',
                'status'    => 1
            ]
        ]);
    }
}
