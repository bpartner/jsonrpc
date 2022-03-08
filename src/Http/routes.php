<?php

use Bpartner\Jsonrpc\Middlewares\BaseJsonRpcMiddleware;

Route::group(
    ['namespace' => 'Bpartner\Jsonrpc\Http',
     'middleware' => ['simple-rpc', BaseJsonRpcMiddleware::class],
    ], function () {
    Route::post('jsonrpc/v1/endpoint', 'RpcController')->name('api.rpc');
});
