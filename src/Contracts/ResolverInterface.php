<?php

namespace Bpartner\Jsonrpc\Contracts;

use Bpartner\Jsonrpc\RpcRequest;
use Bpartner\Jsonrpc\RpcResponse;

interface ResolverInterface
{
    public function __construct(RpcRequest $request, RpcResponse $response);
    public function resolveHandler(): string;
    public function request(): RpcRequest;
    public function handler(): BaseRpc;
}
