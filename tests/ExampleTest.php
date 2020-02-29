<?php

namespace Bpartner\Jsonrpc\Tests;

use Bpartner\Jsonrpc\JsonrpcServiceProvider;
use Orchestra\Testbench\TestCase;

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
