<?php

namespace Pedroni\RdStation\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Pedroni\RdStation\RdStationServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pedroni\\RdStation\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
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
        config()->set('rd_station.api_key', 'TEST_API_KEY');

        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_rd-station_table.php.stub';
        $migration->up();
        */
    }
}
