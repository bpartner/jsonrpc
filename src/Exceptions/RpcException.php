<?php

namespace Bpartner\Jsonrpc\Exceptions;

use Exception;

class RpcException extends Exception
{
    /** @var \Bpartner\Jsonrpc\RpcResponse */
    private $response;

    public function __construct(array $response)
    {
        $this->response = $response;
        $message = $response['error']['message'];
        parent::__construct($message);
    }

    public function render()
    {
        return $this->response;
    }
}
