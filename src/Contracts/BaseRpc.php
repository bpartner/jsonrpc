<?php

namespace Bpartner\Jsonrpc\Contracts;

use Bpartner\Jsonrpc\Exceptions\ValidatorError;
use Bpartner\Jsonrpc\RpcRequest;
use Bpartner\Jsonrpc\RpcResponse;
use Illuminate\Support\Facades\Validator;

abstract class BaseRpc
{
    /**
     * @var array
     */
    protected $params;
    /**
     * @var \Bpartner\Jsonrpc\RpcResponse
     */
    public $response;

    /**
     * @return array
     */
    abstract public function handle(): array;

    /**
     * BaseRpc constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcRequest $request
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\ValidatorError
     */
    public function __construct(RpcRequest $request)
    {
        $this->params = $request->params();
        $this->validateParams();
        $this->response = RpcResponse::make()->setId($request->id());
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
            throw new ValidatorError(implode('; ', $validator->errors()->all()));
        }
    }

    protected function error(string $message, int $code = RpcResponse::INVALID_PARAM, array $data = [])
    {
        $this->response->setError($message, $code, $data);
    }

    public function setResult(array $data): RpcResponse
    {
        return $this->response->setResult($data);
    }
}
