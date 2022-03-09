<?php

namespace Pedroni\RdStation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Pedroni\RdStation\Commands\RdStationCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_rd-station_table')
            ->hasCommand(RdStationCommand::class);
    }
}
