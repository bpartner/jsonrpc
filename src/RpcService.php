<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Exceptions\RpcException;
use Bpartner\Jsonrpc\Exceptions\ValidatorError;

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
     * @param \Bpartner\Jsonrpc\RpcRequest $data
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    public function __construct(RpcRequest $data)
    {
        $this->request = $data;
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
        $namespace = $this->getNamespace();
        $class = $this->request->method();

        try {
            $rpc = $namespace.ucfirst($class);
            $this->handler = new $rpc($this->request);
        } catch (ValidatorError $exception) {
            $this->response->setError(
                'RPC: Invalid param: '.$exception->getMessage(),
                RpcResponse::INVALID_PARAM,
                $this->request->toArray()
            );

            throw new RpcException($this->response);
        } catch (\Exception | \Throwable $exception) {
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
}
