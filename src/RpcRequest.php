<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Contracts\BaseRpc;
use Bpartner\Jsonrpc\Contracts\RpcRequestInterface;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class RpcRequest.
 */
class RpcRequest implements RpcRequestInterface
{
    protected string $method;
    protected array $params;
    protected string $id;

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
     * @return void
     */
    private function init($data): void
    {
        if (empty($data) && app()->runningInConsole()) {
            return;
        }

        $this->id = $data['id'] ?? '';
        $this->method = $data['method'];
        $this->params = $data['params'] ?? [];
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
    #[ArrayShape(['id' => "string", 'method' => "string", 'params' => "array", 'jsonrpc' => "string"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
            'jsonrpc' => '2.0',
        ];
    }
}
