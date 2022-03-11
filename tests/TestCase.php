<?php

namespace Pedroni\RdStation\Tests;

use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use Pedroni\RdStation\Controllers\OAuthCallback;
use Pedroni\RdStation\Controllers\OAuthInstall;
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
        config()->set('rd_station.api_key', 'TEST_API_KEY');
        config()->set('rd_station.client_id', 'TEST_CLIENT_ID');
        config()->set('rd_station.client_secret', 'TEST_CLIENT_SECRET');
        config()->set('rd_station.redirect_url', 'https://example.com/auth/callback');

        config()->set('database.default', 'testing');

        $migration = include __DIR__ . '/../database/migrations/create_rd_station_config_table.php';
        $migration->up();
    }

    /**
     * @param Router $router
     */
    public function defineRoutes($router)
    {
        $router->get('rd-station/oauth/install', OAuthInstall::class);
        $router->get('rd-station/oauth/callback', OAuthCallback::class);
    }
}
