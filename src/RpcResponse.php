<?php

namespace Bpartner\Jsonrpc;

use Illuminate\Support\Fluent;

class RpcResponse
{
    public const PARSE_ERROR = -32700;
    public const INTERNAL_ERROR = -32603;
    public const METHOD_NOT_FOUND = -32601;
    public const INVALID_PARAM = -32602;

    /** @var array */
    private $result;

    /** @var string */
    private $id;

    /**
     * @var \Illuminate\Support\Fluent
     */
    private $error;

    /**
     * @var array
     */
    private $status;

    /** @var string */
    private $rpc_methodName;

    /**
     * Create new instance.
     *
     * @return static
     */
    public static function make(): RpcResponse
    {
        return new static();
    }

    /**
     * Make Error response.
     *
     * @param string $message
     * @param int    $code
     * @param null   $data
     *
     * @return array
     */
    public function responseError(string $message, $code = self::INTERNAL_ERROR, $data = null): array
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
        if (!$this->id and !$this->error) {
            return [];
        }

        $response = [
            'jsonrpc' => '2.0',
            'id'      => $this->id,
        ];

        if ($this->error) {
            $response['error'] = $this->error;
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
    public function setResult(array $data)
    {
        $this->status = [
            'message' => "RPC: ({$this->rpc_methodName}): successfully completed",
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

    public function setError(string $message, $code = self::INTERNAL_ERROR, $data = null): RpcResponse
    {
        $this->error = new Fluent([
            'request' => $data,
            'code'    => $code,
        ]);

        $this->status = [
            'message' => "RPC: ({$this->rpc_methodName}): {$message}",
            'status'  => 'error',
        ];

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
}
