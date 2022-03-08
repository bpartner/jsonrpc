<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Contracts\BaseRpc;
use Bpartner\Jsonrpc\Contracts\ResolverInterface;
use Bpartner\Jsonrpc\Exceptions\RpcException;
use Bpartner\Jsonrpc\Exceptions\ValidatorError;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

class RpcResolver implements ResolverInterface
{
    protected RpcRequest $request;
    protected RpcResponse $response;
    protected BaseRpc $handler;

    public function __construct(RpcRequest $request, RpcResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->response->setId($request->id())->setRpcMethodName($request->method());
    }

    /**
     * @return string
     */
    public function resolveHandler(): string
    {
        $class = $this->request->method();

        return Handlers::resolveClassname(Str::camel($class));
    }

    public function request(): RpcRequest
    {
        return $this->request;
    }

    /**
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    public function handler(): BaseRpc
    {
        if (isset($this->handler)) {
            return $this->handler;
        }

        try {
            $this->handler = app($this->resolveHandler());
        } catch (ValidatorError $exception) {
            $this->response->setError(
                $exception->getMessage(),
                RpcResponse::INVALID_PARAM,
                $this->request->toArray()
            );

            throw new RpcException($this->response);
        } catch (BindingResolutionException $exception) {
            $this->response->setError(
                'Method not found',
                RpcResponse::METHOD_NOT_FOUND,
                $this->request->toArray()
            );

            throw new RpcException($this->response);
        }

        return $this->handler;
    }

    public function setHandler(BaseRpc $handler): void
    {
        $this->handler = $handler;
    }
}
