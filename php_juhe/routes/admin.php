<?php
Route::group(['domain'=>config('app.admin_domain')], function () {

    Route::get('login', 'LoginController@show')->name('admin.login');  // 登录页面
    Route::post('login', 'LoginController@login')->name('admin.login');// 登录

    //登录验证
    Route::group(['middleware' => ['auth:admin']], function () {
        //退出
        Route::get('dropout','LoginController@dropout')->name('admin.dropout');
        //修改资料
        Route::get('info','IndexController@info')->name('admin.info');
        Route::post('pass', 'IndexController@pass')->name('admin.pass');

        //后台首页
        Route::get('/', 'IndexController@index')->name('admin.index');
            //权限管理
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('power','PowerController@index')->name('power.index');
                Route::get('power/create','PowerController@create')->name('power.create');
                Route::post('power/store', 'PowerController@store')->name('power.store');
                Route::delete('power/delete', 'PowerController@delete')->name('power.delete');
                Route::get('power/{power}/edit', 'PowerController@edit')->name('power.edit');
                Route::post('power/{id}/update', 'PowerController@update')->name('power.update');
            });
            //角色管理
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('roles','RolesController@index')->name('roles.index');
                Route::get('roles/create','RolesController@create')->name('roles.create');
                Route::post('roles/store', 'RolesController@store')->name('roles.store');
                Route::delete('roles/delete', 'RolesController@delete')->name('roles.delete');
                Route::get('roles/{role}/edit', 'RolesController@edit')->name('roles.edit');
                Route::post('roles/{id}/update', 'RolesController@update')->name('roles.update');
            });
            //管理员管理
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('admins','AdminsController@index')->name('admins.index');
                Route::get('admins/create','AdminsController@create')->name('admins.create');
                Route::post('admins/store', 'AdminsController@store')->name('admins.store');
                Route::delete('admins/delete', 'AdminsController@delete')->name('admins.delete');
                Route::get('admins/{admins}/edit', 'AdminsController@edit')->name('admins.edit');
                Route::post('admins/{id}/update', 'AdminsController@update')->name('admins.update');
            });

            //系统日志
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('syslog','SyslogController@index')->name('syslog.index');
                Route::delete('syslog/delete', 'SyslogController@delete')->name('syslog.delete');
            });

            //系统配置
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('sysconfig','SysconfigController@index')->name('sysconfig.index');
                Route::post('sysconfig/update', 'SysconfigController@update')->name('sysconfig.update');
            });

            //会员管理
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('users','UsersController@index')->name('users.index');
                Route::get('users/create','UsersController@create')->name('users.create');
                Route::post('users/store', 'UsersController@store')->name('users.store');
                Route::delete('users/delete', 'UsersController@delete')->name('users.delete');
                Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
                Route::post('users/{id}/update', 'UsersController@update')->name('users.update');
                Route::get('users/{user}/rates','UsersController@rates')->name('users.rates');
                Route::post('users/{user}/rates','UsersController@ratesStore')->name('users.rates.store');
                Route::get('users/{user}/freeze','UsersController@freeze')->name('users.freeze');
                Route::post('users/{user}/freeze','UsersController@addfreezes')->name('users.addfreezes');
            });
            //接口类型
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('apistyle','ApistyleController@index')->name('apistyle.index');
                Route::get('apistyle/create','ApistyleController@create')->name('apistyle.create');
                Route::post('apistyle/store', 'ApistyleController@store')->name('apistyle.store');
                Route::delete('apistyle/delete', 'ApistyleController@delete')->name('apistyle.delete');
                Route::get('apistyle/{apistyle}/edit', 'ApistyleController@edit')->name('apistyle.edit');
                Route::post('apistyle/{id}/update', 'ApistyleController@update')->name('apistyle.update');
                Route::get('apistyle/{apistyle}/round', 'ApistyleController@round')->name('apistyle.round');
                Route::post('apistyle/{id}/round', 'ApistyleController@roundStore')->name('apistyle.roundstore');
            });
            //上游类型
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('upstyle','UpstyleController@index')->name('upstyle.index');
                Route::get('upstyle/create','UpstyleController@create')->name('upstyle.create');
                Route::post('upstyle/store', 'UpstyleController@store')->name('upstyle.store');
                Route::delete('upstyle/delete', 'UpstyleController@delete')->name('upstyle.delete');
                Route::get('upstyle/{upstyle}/edit', 'UpstyleController@edit')->name('upstyle.edit');
                Route::post('upstyle/{id}/update', 'UpstyleController@update')->name('upstyle.update');
            });
            //上游账户
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('upaccount','UpaccountController@index')->name('upaccount.index');
                Route::get('upaccount/create','UpaccountController@create')->name('upaccount.create');
                Route::post('upaccount/store', 'UpaccountController@store')->name('upaccount.store');
                Route::delete('upaccount/delete', 'UpaccountController@delete')->name('upaccount.delete');
                Route::get('upaccount/{upaccount}/edit', 'UpaccountController@edit')->name('upaccount.edit');
                Route::post('upaccount/{id}/update', 'UpaccountController@update')->name('upaccount.update');
                Route::post('upaccount/changechoose','UpaccountController@changechoose')->name('upaccount.changechoose');
            });
            //财务管理
            Route::group(['middleware' => ['check.power']], function () {
                //提现管理
                Route::get('finances/index/{status?}','FinancesController@index')->name('finances.index');
                Route::get('finances/{tixian}/edit', 'FinancesController@edit')->name('finances.edit');
                Route::delete('finances/update/{act}', 'FinancesController@update')->name('finances.update');
                Route::post('finances/{tixian}/memo', 'FinancesController@memo')->name('finances.memo');//添加备注
                Route::get('finances/{tixian}/notify', 'FinancesController@notify')->name('finances.notify');//添加备注
                //资金变动
                Route::get('finances/moneylog','MoneyLogController@index')->name('finances.moneylog');
                //通道统计
                Route::get('finances/tj','MoneyLogController@tj')->name('finances.tj');
            });
            //提现卡号
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('bankcard','BankcardController@index')->name('bankcard.index');
                Route::delete('bankcard/delete', 'BankcardController@delete')->name('bankcard.delete');
            });
            //订单管理
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('order','OrderController@index')->name('order.index');
                Route::get('order/{order}/edit','OrderController@edit')->name('order.edit');
                Route::post('order/{order}/budan','OrderController@budan')->name('order.budan');
                Route::post('order/{order}/Notice','OrderController@notice')->name('order.notice');
                Route::post('order/{order}/back','OrderController@back')->name('order.back');
                //接口日志
                Route::get('paylog','OrderController@payLog')->name('paylog.index');
                Route::delete('paylog/delete', 'OrderController@payLogDelete')->name('paylog.delete');
            });
            //客户端黑名单
            Route::group(['middleware' => ['check.power']], function () {
                Route::get('blacklist','BlacklistController@index')->name('blacklist.index');
                Route::post('blacklist/store', 'BlacklistController@store')->name('blacklist.store');
                Route::delete('blacklist/delete', 'BlacklistController@delete')->name('blacklist.delete');
                //代付订单查看
                Route::get('checkneworder','IndexController@checkneworder')->name('admin.checkneworder');
            });
    });
});
