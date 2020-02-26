<?php

namespace Bpartner\Jsonrpc;


/**
 * Class RpcRequest
 *
 */
class RpcRequest
{
    /** @var string */
    protected $method;

    /** @var array */
    protected $params;

    /** @var string */
    protected $id;

    /**
     * RpcRequest constructor.
     *
     * @param \Bpartner\Jsonrpc\RpcFormRequest $request
     */
    public function __construct(RpcFormRequest $request)
    {
        $inputData = $request->all();
        $this->init($inputData);
    }

    /**
     * @param $data
     *
     * @return void|array
     */
    private function init($data)
    {
        if (app()->runningInConsole()) {
            if (empty($data)) {
                return [];
            }
        }

        $this->id = $data['id'];
        $this->method = $data['method'];
        $this->params = $data['params'];
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
            'jsonrpc' => "2.0"
        ];
    }
}
