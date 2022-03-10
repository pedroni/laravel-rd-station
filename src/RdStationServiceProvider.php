<?php

namespace Pedroni\RdStation;

use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Commands\RdStationCommand;
use Pedroni\RdStation\Repositories\ContactRepository;
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
            ->hasCommand(RdStationCommand::class);
    }

    public function packageRegistered()
    {
        $this->app->singleton(
            RdStationClient::class,
            fn () =>
            new RdStationClient(
                Http::baseUrl(config('rd_station.base_url'))
            )
        );

        $this->app->singleton(
            ContactRepository::class,
            fn () =>
            new ContactRepository($this->app->make(RdStationClient::class))
        );

        $this->app->singleton('rd-station', fn () => new RdStation(
            $this->app->make(ContactRepository::class)
        ));
    }
}
