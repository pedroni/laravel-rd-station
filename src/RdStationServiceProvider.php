<?php

namespace Pedroni\RdStation;

use Pedroni\RdStation\Support\RdStationConfig;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RdStationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('rd-station')
            ->hasConfigFile('rd_station')
            ->hasMigration('create_rd_station_config_table');
    }

    public function packageRegistered()
    {
        $this->app->singleton(
            RdStationClient::class,
            fn () =>
            new RdStationClient(
                config('rd_station.base_url'),
                config('rd_station.api_key'),
            )
        );

        $this->app->singleton(
            RdStationConfig::class,
            fn () =>
            RdStationConfig::make(
                config('rd_station.base_url'),
                config('rd_station.client_id'),
                config('rd_station.client_secret'),
                config('rd_station.redirect_path'),
            )
        );

        $this->app->singleton('rd-station', fn () => $this->app->make(RdStation::class));
    }
}
