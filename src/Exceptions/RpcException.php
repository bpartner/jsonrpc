<?php

namespace Bpartner\Jsonrpc\Exceptions;

use Bpartner\Jsonrpc\RpcResponse;
use Exception;

class RpcException extends Exception
{
    /** @var \Bpartner\Jsonrpc\RpcResponse */
    private $response;

    public function __construct(RpcResponse $response)
    {
        $this->response = $response;
        $message = $response->getErrorMessage();
        parent::__construct($message);
    }

    /**
     * @return array
     */
    public function render()
    {
        return $this->response->toArray();
    }

    public function report()
    {
        logger()->error($this->response->getErrorMessage(), $this->response->toArray());
    }
}
