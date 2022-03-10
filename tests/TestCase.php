<?php

namespace Pedroni\RdStation\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pedroni\RdStation\RdStationServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RdStationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('rd_station.private_token', 'TEST_PRIVATE_TOKEN');
    }
}
