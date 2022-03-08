<?php

namespace Bpartner\Jsonrpc;

use Bpartner\Jsonrpc\Contracts\ResolverInterface;
use Bpartner\Jsonrpc\Contracts\RpcServiceInterface;

class RpcService implements RpcServiceInterface
{
    protected RpcResolver $resolver;
    public Contracts\BaseRpc $handler;

    /**
     * RpcService constructor.
     *
     * @param  \Bpartner\Jsonrpc\RpcResolver  $resolver
     *
     * @throws \Bpartner\Jsonrpc\Exceptions\RpcException
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        $this->handler = $this->resolver->handler();
    }

    /**
     * Run RPC method.
     *
     *
     * @return array
     * @throws \Exception
     */
    public function run(): array
    {
        return $this->handler
            ->getResponse($this->handler->handle())
            ->toArray();
    }
}
