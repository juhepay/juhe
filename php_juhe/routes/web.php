<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['domain'=>config('app.web_domain')], function () {
    Route::get('login', 'LoginController@show')->name('member.login');  // 登录页面
    Route::post('login', 'LoginController@login')->name('member.login');// 登录
    //登录验证
    Route::group(['middleware' => ['auth:user']], function () {
        //退出
        Route::get('dropout','LoginController@dropout')->name('member.dropout');
        //后台首页
        Route::get('/', 'IndexController@index')->name('member.index');
        //我的资料
        Route::get('info', 'IndexController@info')->name('member.info');
        //修改资料
        Route::post('pass', 'IndexController@pass')->name('member.pass');
        //提现账户
        Route::get('bankcard','BankcardController@index')->name('member.bankcard.index');
        Route::get('bankcard/create','BankcardController@create')->name('member.bankcard.create');
        Route::post('bankcard/store', 'BankcardController@store')->name('member.bankcard.store');
        Route::delete('bankcard/delete', 'BankcardController@delete')->name('member.bankcard.delete');
        Route::get('bankcard/{bankcard}/edit', 'BankcardController@edit')->name('member.bankcard.edit');
        Route::post('bankcard/{id}/update', 'BankcardController@update')->name('member.bankcard.update');
        //提款提现
        Route::get('tixian','TixianController@index')->name('member.tixian.index');
        Route::get('tixian/create','TixianController@create')->name('member.tixian.create');
        Route::post('tixian/store', 'TixianController@store')->name('member.tixian.store');
        //资金变动
        Route::get('moneylog','MoneyLogController@index')->name('member.moneylog.index');
        //费率详情
        Route::get('rate','IndexController@rate')->name('member.rate.index');
        //充值
        Route::get('recharge','IndexController@recharge')->name('member.recharge');
        Route::post('recharge','IndexController@rechargeStore')->name('member.recharge');
        //订单记录
        Route::get('order','OrderController@index')->name('member.order');
        //查看密钥
        Route::post('getuserkey','IndexController@getUserKey')->name('member.getuserkey');
        //重置密钥
        Route::post('resetuserkey','IndexController@resetUserKey')->name('member.resetuserkey');
        //下级商户
        Route::get('dluser','AgentController@dluser')->name('member.dluser');
        //自助开户
        Route::get('dladduser','AgentController@dladduser')->name('member.dladduser');
        Route::post('dladduser','AgentController@storeUser')->name('member.dladduser');
        //费率配置
        Route::get('dlfl/{user}','AgentController@dlfl')->name('member.dlfl');
        Route::post('dlfl/{user}','AgentController@storeFl')->name('member.dlfl');
        //下级交易订单
        Route::get('dldd','AgentController@dldd')->name('member.dldd');
    });
});

