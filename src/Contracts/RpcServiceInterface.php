<?php

namespace Bpartner\Jsonrpc\Contracts;


interface RpcServiceInterface
{
    public function __construct(ResolverInterface $resolver);

    public function run(): array;
}
