<?php

namespace Bpartner\Jsonrpc\Contracts;

use Bpartner\Jsonrpc\Exceptions\ValidatorError;
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
     * @param array $params
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\ValidatorError
     */
    public function __construct(array $params)
    {
        $this->params = $params;
        $this->validateParams();
        $this->response = new RpcResponse();
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
        return $this->response->setError($message, $code, $data);
    }
}
