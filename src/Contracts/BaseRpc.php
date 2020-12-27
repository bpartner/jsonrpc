<?php

namespace Bpartner\Jsonrpc\Contracts;

use Bpartner\Jsonrpc\Exceptions\RpcException;
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
     * @param \Bpartner\Jsonrpc\RpcRequest  $request
     * @param \Bpartner\Jsonrpc\RpcResponse $response
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\ValidatorError
     */
    public function __construct(RpcRequest $request, RpcResponse $response)
    {
        $this->params = $request->params();
        $this->validateParams();
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
            throw new ValidatorError(implode('; ', $validator->errors()->all()));
        }
    }

    /**
     * @param string $message
     * @param int    $code
     * @param array  $data
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    protected function error(string $message, int $code = RpcResponse::INVALID_PARAM, array $data = [])
    {
        $this->response->setError($message, $code, $data);
        throw new RpcException($this->response);
    }

    public function getResponse(array $data): RpcResponse
    {
        return $this->response->setResult($data);
    }
}
