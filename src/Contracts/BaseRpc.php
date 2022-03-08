<?php

namespace Bpartner\Jsonrpc\Contracts;

use Bpartner\Jsonrpc\Exceptions\RpcException;
use Bpartner\Jsonrpc\Exceptions\ValidatorError;
use Bpartner\Jsonrpc\RpcRequest;
use Bpartner\Jsonrpc\RpcResponse;
use Illuminate\Support\Facades\Validator;

abstract class BaseRpc
{
    protected string|array $middlewares = 'guest';

    protected array $params;
    public RpcResponse $response;

    /**
     * @throws \Bpartner\Jsonrpc\Exceptions\ValidatorError
     */
    public function handle(): array
    {
        $this->validateParams();
        return $this->call();
    }

    abstract public function call(): array;

    /**
     * BaseRpc constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcRequest  $request
     * @param \Bpartner\Jsonrpc\RpcResponse $response
     *
     */
    public function __construct(RpcRequest $request, RpcResponse $response)
    {
        $this->params = $request->params();
        $this->response = $response
            ->setId($request->id())
            ->setRpcMethodName(class_basename($this));
    }

    /**
     * @return array
     */
    protected function rule(): array
    {
        return [];
    }

    /**
     * @throws \Bpartner\Jsonrpc\Exceptions\ValidatorError
     */
    protected function validateParams(): void
    {
        $validator = Validator::make($this->params, $this->rule());
        if ($validator->fails()) {
            $messages = $validator->errors();
            throw new ValidatorError($this->response, $messages->all(), $messages->messages());
        }
    }

    /**
     * @param string $message
     * @param int    $code
     * @param array  $data
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    protected function error(string $message, int $code = RpcResponse::INVALID_PARAM, array $data = []): void
    {
        $this->response->setError($message, $code, $data);
        throw new RpcException($this->response);
    }

    public function getResponse(array $data): RpcResponse
    {
        return $this->response->getResponse($data);
    }

    public function middlewares(): array
    {
        if (is_array($this->middlewares)) {
            return $this->middlewares;
        }

        return $this->prepareMiddlewares(config("jsonrpc.middleware.$this->middlewares") ?? []);
    }

    private function prepareMiddlewares($middlewares): array
    {
        $result = [];
        foreach ($middlewares as $middleware) {
            $isPresentInConfig = config('jsonrpc.middleware.'.$middleware);
            if ($isPresentInConfig) {
                $result[] = config('jsonrpc.middleware.'.$middleware);
                continue;
            }
            $result[] = $middleware;
        }

        return collect($result)->flatten()->unique()->toArray();
    }

}
