<?php

namespace Bpartner\Jsonrpc\Exceptions;

use Bpartner\Jsonrpc\RpcResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ValidatorError extends Exception
{
    public function __construct(
        private RpcResponse $response,
        $messages,
        $bags
    ) {
        $this->response->setError(message: $messages, bags: $bags);
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json($this->response->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
