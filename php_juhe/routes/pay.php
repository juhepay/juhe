<?php
Route::group(['domain'=>config('app.pay_domain')], function () {
    Route::post('/', 'DefaultController@index')->name('pay.index');
    Route::post('notify/{style}', 'DefaultController@notify')->name('pay.notify');//异步回调
    Route::get('notify/{style}', 'DefaultController@notify')->name('pay.notify');//异步回调
    Route::get('backurl/{orderno}', 'DefaultController@backurl')->name('pay.backurl');//同步回调
    Route::get('form_post', 'DefaultController@formPost')->name('form_post');
    Route::get('go','DefaultController@go')->name('pay.go');//获取浏览器指纹页面
    Route::post('client','DefaultController@client')->name('pay.client');//客户端验证

    Route::post('order/query','DefaultController@query')->name('pay.query');//订单查询
    Route::post('ekofapy','PrePaymentController@index')->name('pay.ekofapy');//代付
    Route::post('ekofapy/query','PrePaymentController@query')->name('pay.ekofapy.query');//代付查询

    Route::get('scan','DefaultController@scan')->name('pay.scan');//二维码页面
    Route::get('cacheImg','DefaultController@cacheImg')->name('pay.cacheImg');//获取二维码
    Route::post('getddhstatus','DefaultController@getddhstatus')->name('pay.getddhstatus');//订单状态监测
});
