<?php

namespace Bpartner\Jsonrpc;

use Illuminate\Support\Fluent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class RpcResponse
{
    public const PARSE_ERROR = -32700;
    public const INTERNAL_ERROR = -32603;
    public const METHOD_NOT_FOUND = -32601;
    public const INVALID_PARAM = -32602;

    private array $result;
    private string $id;
    private Fluent $error;
    private array $status;
    private string $rpc_methodName;

    /**
     * Create new instance.
     *
     * @return static
     */
    #[Pure]
    public static function make(): RpcResponse
    {
        return new static();
    }

    public function __construct()
    {
        $this->error = new Fluent();
    }

    /**
     * Make Error response.
     *
     * @param string $message
     * @param  int  $code
     * @param null   $data
     *
     * @return array
     */
    #[ArrayShape(['jsonrpc' => "string", 'id' => "mixed|null", 'error' => "array"])]
    public function responseError(string $message, int $code = self::INTERNAL_ERROR, $data = null): array
    {
        return [
            'jsonrpc' => '2.0',
            'id'      => $data['id'] ?? null,
            'error'   => [
                'message' => $message,
                'data'    => $data,
                'code'    => $code,
            ],
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (!$this->id && !$this->error->toArray()) {
            return [];
        }

        $response = [
            'jsonrpc' => '2.0',
            'id'      => $this->id,
        ];

        if ($this->error->toArray()) {
            $response['error'] = $this->error->toArray();
        } else {
            $response['result'] = $this->result;
        }

        if (config('jsonrpc.status_message')) {
            $response['status'] = $this->status;
        }

        return $response;
    }

    /**
     * @param array $data
     *
     * @return \Bpartner\Jsonrpc\RpcResponse
     */
    public function setResult(array $data): RpcResponse
    {
        $this->status = [
            'message' => "RPC: ($this->rpc_methodName): successfully completed",
            'status'  => 'success',
        ];

        $this->result = $data;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): RpcResponse
    {
        $this->id = $id;
        return $this;
    }

    public function setError(string|array $message, $code = self::INTERNAL_ERROR, $data = null, array $bags = []): RpcResponse
    {
        $this->error = new Fluent([
            'code'    => $code,
        ]);

        if ($data && config('jsonrpc.error_with_request')) {
            $this->error->request = $data;
        }

        $this->status = [
            'method' => "RPC: ($this->rpc_methodName)",
            'message' => is_string($message) ? $message : implode('; ', $message),
            'status'  => 'error',
        ];
        if ($bags) {
            $this->status['bags'] = $bags;
        }

        return $this;
    }

    public function getErrorMessage(): string
    {
        return $this->status['message'] ?? 'undefined';
    }

    public function setRpcMethodName(string $basename): RpcResponse
    {
        $this->rpc_methodName = $basename;

        return $this;
    }

    public function getResponse(array $data): RpcResponse
    {
        if ($this->error->toArray()) {
            return $this;
        }
        return $this->setResult($data);
    }
}
