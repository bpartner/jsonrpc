<?php

namespace Bpartner\Jsonrpc\Http;

use Closure;
use Illuminate\Http\Request;

class RpcMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() !== config('jsonrpc.token')) {
            return  response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthorized',
            ]);
        }

        return $next($request);
    }
}
