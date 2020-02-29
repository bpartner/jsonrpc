<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Commands\CreateRpcHandler;
use Bpartner\Jsonrpc\Http\RpcMiddleware;
use Illuminate\Support\ServiceProvider;

class JsonrpcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register default route
        if (config('jsonrpc.use_default_route')) {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('jsonrpc.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                CreateRpcHandler::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'jsonrpc');

        // Register simple-rpc middleware for default route
        if (config('jsonrpc.use_default_route')) {
            $this->app['router']->aliasMiddleware('simple-rpc', RpcMiddleware::class);
        }
    }
}
