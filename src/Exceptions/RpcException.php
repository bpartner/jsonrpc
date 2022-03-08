<?php

namespace Bpartner\Jsonrpc\Exceptions;

use Bpartner\Jsonrpc\RpcResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class RpcException extends Exception
{
    private RpcResponse $response;

    public function __construct(RpcResponse $response)
    {
        $this->response = $response;
        $message = $response->getErrorMessage();
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json($this->response->toArray(), 400);
    }

    public function report(): void
    {
        logger()->error($this->response->getErrorMessage(), $this->response->toArray());
    }
}
