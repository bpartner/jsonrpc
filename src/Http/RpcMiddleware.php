<?php

namespace Bpartner\Jsonrpc\Http;

use Closure;

class RpcMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
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
