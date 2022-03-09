<?php

namespace Pedroni\RdStation;

use Pedroni\RdStation\Commands\RdStationCommand;
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
            ->hasConfigFile()
            ->hasCommand(RdStationCommand::class);
    }
}
