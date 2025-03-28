# Integrate your Laravel project with RD Station

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pedroni/laravel-rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/laravel-rd-station)
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/pedroni/laravel-rd-station/.github%2Fworkflows%2Frun-tests.yml)
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/pedroni/laravel-rd-station/.github%2Fworkflows%2Fphpstan.yml?label=phpstan)
[![Total Downloads](https://img.shields.io/packagist/dt/pedroni/laravel-rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/laravel-rd-station)

This is a Laravel wrapper around the RD Station API.

## Laravel Support Matrix

This table outlines the Laravel versions supported by each version of our library.

| Library Version | Laravel Version |
| --------------- | --------------- |
| 1.x, 2.x, 3.x   | 8.x             |
| 4.x             | 9.x             |
| 5.x             | 10.x            |
| 6.x             | 11.x            |
| 7.x             | 12.x            |

## Installation

You can install the package via composer:

```bash
composer require pedroni/laravel-rd-station
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="rd-station-config"
```

This is the contents of the published config file:

```php
return [
    'base_url' => env('RD_STATION_BASE_URL', 'https://api.rd.services'),
    'client_id' => env('RD_STATION_CLIENT_ID'), // REQUIRED
    'client_secret' => env('RD_STATION_CLIENT_SECRET'), // REQUIRED
    'redirect_path' => env('RD_STATION_REDIRECT_PATH', 'rd-station/oauth/callback'),
];
```

> ⚠️ The env variable `RD_STATION_REDIRECT_PATH` **MUST** match your route endpoint that will be configured later

Publish migrations files and run the migrations:

```bash
php artisan vendor:publish --tag="rd-station-migrations"
php artisan migrate
```

Add two `GET` routes for the installation controller and the callback controller, example:

```php
// routes/web.php

use Pedroni\RdStation\Controllers\OAuthInstall;
use Pedroni\RdStation\Controllers\OAuthCallback;

Route::get('rd-station/oauth/install', OAuthInstall::class);
Route::get('rd-station/oauth/callback', OAuthCallback::class); // recommended
```

Open a browser window on http://your-domain.com/rd-station/oauth/install to initiate the instalation.

> ⚠️ If you decide to change the recommended callback URL you **MUST** change the `RD_STATION_REDIRECT_PATH` variable

## Usage

### Using the facade

```php
use Pedroni\RdStation\Facades\RdStation;

RdStation::events()->conversion([
    'email' => 'example@mail.com',
    'conversion_identifier' => 'identifier',
    'cf_example' => 'An example of custom field',
    'tags' => ['example-tag'],
]);
```

### Using dependency injection

```php
use Pedroni\RdStation;

public function ExampleController
{
    public function exampleUsingAnArgument(RdStation $rdStation)
    {
        $rdStation->events()->conversion([...]);
    }

    public function exampleUsingLaravelContainer()
    {
        $rdStation = app()->make(RdStation::class);

        $rdStation->events()->conversion([...]);
    }
}

```

## Testing

```bash
composer test
docker run --rm -v $(pwd):/app -w /app composer:latest bash -c "composer install && vendor/bin/pest"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Lucas Pedroni](https://github.com/pedroni)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
