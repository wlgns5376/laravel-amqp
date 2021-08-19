<?php

namespace Wlgns5376\LaravelAmqp\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Wlgns5376\LaravelAmqp\AmqpServiceProvider',
        ];
    }
}