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

    public function packageRegistered(): void
    {
        $this->app->bind(
            RdStationConfig::class,
            function () {
                /** @var string */
                $baseUrl = config('rd_station.base_url');
                /** @var string */
                $clientId = config('rd_station.client_id');
                /** @var string */
                $clientSecret = config('rd_station.client_secret');
                /** @var string */
                $redirectPath = config('rd_station.redirect_path');

                return RdStationConfig::make(
                    $baseUrl,
                    $clientId,
                    $clientSecret,
                    $redirectPath,
                );
            }
        );

        $this->app->singleton('rd-station', fn () => $this->app->make(RdStation::class));
    }
}
