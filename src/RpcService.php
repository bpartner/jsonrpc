<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Exceptions\RpcException;
use Bpartner\Jsonrpc\Exceptions\ValidatorError;

class RpcService
{
    /**
     * @var \Bpartner\Jsonrpc\RpcRequest
     */
    private $data;
    /**
     * @var \Bpartner\Jsonrpc\RpcResponse
     */
    private $response;

    /** @var \Bpartner\Jsonrpc\Contracts\BaseRpc */
    private $handler;

    /**
     * RpcService constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcRequest $data
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    public function __construct(RpcRequest $data)
    {
        $this->data = $data;
        $this->response = new RpcResponse();
        $this->setHandler();
        $this->response->setId($data->id());
    }

    /**
     * Run RPC method.
     *
     * @return array
     */
    public function run(): array
    {
        $this->make();

        return $this->response->toArray();
    }

    /**
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    private function setHandler()
    {
        $namespace = $this->getNamespace();
        $class = $this->data->method();

        try {
            $rpc = $namespace.ucfirst($class);
            $this->handler = new $rpc($this->data->params());
        } catch (ValidatorError $exception) {
            $response = $this->response->responseError(
                'RPC: Invalid param: '.$exception->getMessage(),
                RpcResponse::INVALID_PARAM,
                $this->data->toArray()
            );

            throw new RpcException($response);
        } catch (\Exception | \Throwable $exception) {
            $response = $this->response->responseError(
                'RPC: Method not found',
                RpcResponse::METHOD_NOT_FOUND,
                $this->data->toArray()
            );

            throw new RpcException($response);
        }
    }

    /**
     * Make your code.
     */
    private function make(): void
    {
        $result = $this->handler->handle();
        $this->response->setResult($result);
    }

    /**
     * Get namespace for handlers.
     *
     * @return string
     */
    private function getNamespace(): string
    {
        return app()->getNamespace().config('jsonrpc.rpc_namespace').'\\';
    }
}
