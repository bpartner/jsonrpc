<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    // Default namespace for handlers generator
    'rpc_namespace' => 'App\RpcHandlers',

    // Bearer token for all request for simple usage
    'token' => env('RPC_TOKEN', '1234567890'),

    // Use default route with simple rpc middleware
    'use_default_route' => false,

    /**
     * DEBUG section
     */

    // Add to response status message
    'status_message' => env('RPC_STATUS_MESSAGE', true),

    // Error status with request
    'error_with_request' => env('RPC_ERROR_WITH_REQUEST', false),
    'middleware' => [
        'guest' => [

        ],
        'auth' => [
            '\App\Http\Middleware\Authenticate:sanctum',
        ]
    ],
];
