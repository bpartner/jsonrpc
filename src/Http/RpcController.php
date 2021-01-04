<?php

namespace Bpartner\Jsonrpc\Http;

use Bpartner\Jsonrpc\RpcFormRequest;
use Bpartner\Jsonrpc\RpcRequest;
use Bpartner\Jsonrpc\RpcService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class RpcController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Invocable method.
     *
     * @param \Bpartner\Jsonrpc\RpcFormRequest $request
     *
     * @return array
     */
    public function __invoke(RpcFormRequest $request): array
    {
        $rpcService = app(RpcService::class);

        return $rpcService->run();
    }
}
