<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        // 用户是否认证验证
        if($exception instanceof AuthenticationException)
        {
            if ( in_array('admin', $exception->guards() ))
            {
                return redirect(route('admin.login'));
            }elseif( in_array('user', $exception->guards()) ){
                return redirect(route('member.login'));
            }

        }else if( $exception instanceof ValidationException){
            $error = Arr::flatten($exception->errors());
            return response()->json(['code'=>0,'msg'=>Arr::get($error, 0)],200,[],256);
        }else if($exception instanceof PowerException){
            if( $request->expectsJson() )
            {
                return response()->json(['code'=>0,'msg'=>'无权访问'],200,[],256);
            }else{
                return response()->view('500', ['code'=>0,'msg'=>'无权访问'], 400);
            }
        }else{
            return response()->json(['code'=>0,'msg'=>$exception->getMessage()],200,[],256);
        }
        return parent::render($request, $exception);
    }
}
