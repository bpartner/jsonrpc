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
        if (!$this->id) {
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

        return $response;
    }

    /**
     * @param array $data
     */
    public function setResult(array $data): void
    {
        $this->result = $data;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setError(string $message, $code = self::INTERNAL_ERROR, $data = null): RpcResponse
    {
        $this->error = new Fluent([
            'message' => $message,
            'data'    => $data,
            'code'    => $code,
        ]);

        return $this;
    }

    public function getErrorMessage()
    {
        return $this->error->message;
    }
}
