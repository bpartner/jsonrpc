<?php

Route::group(['namespace' => 'Bpartner\Jsonrpc\Http', 'middleware' => 'simple-rpc'], function () {
    Route::post('jsonrpc/v1/endpoint', 'RpcController')->name('api.rpc');
});
