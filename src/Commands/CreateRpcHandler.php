<?php

namespace Bpartner\Jsonrpc\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateRpcHandler extends GeneratorCommand
{
    protected $signature = 'make:rpc {name}';

    protected $description = 'Create a new RPC handler';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'RpcHandler';

    public function handle()
    {
        parent::handle();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('jsonrpc.rpc_namespace');
    }

    protected function getStub()
    {
        return __DIR__.'/handler.stub';
    }
}
