<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Exceptions\RpcException;
use Bpartner\Jsonrpc\Exceptions\ValidatorError;
use Illuminate\Contracts\Container\BindingResolutionException;

class RpcService
{
    /**
     * @var \Bpartner\Jsonrpc\RpcRequest
     */
    private $request;
    /**
     * @var \Bpartner\Jsonrpc\RpcResponse
     */
    private $response;

    /** @var \Bpartner\Jsonrpc\Contracts\BaseRpc */
    private $handler;

    /**
     * RpcService constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcRequest  $request
     * @param \Bpartner\Jsonrpc\RpcResponse $response
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    public function __construct(RpcRequest $request, RpcResponse $response)
    {
        $this->request = $request;
        $this->response = $response
            ->setId($request->id())
            ->setRpcMethodName($this->request->method());
        $this->setHandler();

    }

    /**
     * Run RPC method.
     *
     * @return array
     */
    public function run(): array
    {
        return $this->handler
            ->getResponse($this->handler->handle())
            ->toArray();
    }

    /**
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    private function setHandler()
    {
        try {
            $this->handler = app($this->resolveHandler());
        } catch (ValidatorError $exception) {
            $this->response->setError(
                'RPC: Invalid param: ' . $exception->getMessage(),
                RpcResponse::INVALID_PARAM,
                $this->request->toArray()
            );

            throw new RpcException($this->response);
        } catch (BindingResolutionException $exception) {
            $this->response->setError(
                'RPC: Method not found',
                RpcResponse::METHOD_NOT_FOUND,
                $this->request->toArray()
            );

            throw new RpcException($this->response);
        }
    }

    /**
     * Get namespace for handlers.
     *
     * @return string
     */
    private function getNamespace(): string
    {
        return config('jsonrpc.rpc_namespace').'\\';
    }

    /**
     * @return string
     */
    private function resolveHandler(): string
    {
        $namespace = $this->getNamespace();
        $class = $this->request->method();

        return $namespace . ucfirst($class);
    }
}
