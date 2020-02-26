<?php

namespace Bpartner\Jsonrpc\Tests;

use Orchestra\Testbench\TestCase;
use Bpartner\Jsonrpc\JsonrpcServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [JsonrpcServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
