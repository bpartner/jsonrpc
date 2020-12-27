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
     * @var \Bpartner\Jsonrpc\RpcRequest
     */
    private $request;

    /**
     * RpcController constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcFormRequest $request
     */
    public function __construct(RpcFormRequest $request)
    {
        $this->request = new RpcRequest($request);
    }

    /**
     * Invocable method.
     *
     * @return array
     */
    public function __invoke(): array
    {
        $rpcService = app(RpcService::class, [$this->request]);

        return $rpcService->run();
    }
}
