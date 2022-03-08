<?php

namespace Bpartner\Jsonrpc\Middlewares;

use Bpartner\Jsonrpc\Contracts\ResolverInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;

class BaseJsonRpcMiddleware
{
    public function handle(Request $request, $next)
    {
        return $this->checkMiddleware($request, $next);
    }

    private function checkMiddleware($request, $next)
    {
        $middlewares = app(ResolverInterface::class)
            ->handler()
            ->middlewares();

        return app(Pipeline::class)
            ->send($request)
            ->through($middlewares)
            ->then(function ($request) use ($next) {
                return $next($request);
            });
    }
}
