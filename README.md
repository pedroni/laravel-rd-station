# Integrate your Laravel project with RD Station

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pedroni/laravel-rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/laravel-rd-station)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/pedroni/laravel-rd-station/run-tests?label=tests)](https://github.com/pedroni/laravel-rd-station/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/pedroni/laravel-rd-station/Check%20&%20fix%20styling?label=code%20style)](https://github.com/pedroni/laravel-rd-station/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pedroni/laravel-rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/laravel-rd-station)

In development. Feel free to open an issue requesting anything.

> Expect breaking changes on public and internal classes.

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

> 🚨 The env variable for `RD_STATION_REDIRECT_PATH` must match your route endpoint!

Publish migrations file:

```bash
php artisan vendor:publish --tag="rd-station-migrations"
```

> Make sure you run `php artisan migrate` after the migration has been published.

Add two `GET` routes for the installation controller and the callback controller, example:

```php
// routes/web.php
use Pedroni\RdStation\Controllers\OAuthInstall;
use Pedroni\RdStation\Controllers\OAuthCallback;

Route::get('rd-station/oauth/install', OAuthInstall::class);
Route::get('rd-station/oauth/callback', OAuthCallback::class); // recommended
```

Access in your browser http://example.com/rd-station/oauth/install to initiate the instalation.

> 🚨 If you change the recommended callback URL you **MUST** change the `RD_STATION_REDIRECT_PATH` env variable!

## Usage

### Using the facade

```php
use Pedroni\RdStation\Facades\RdStation;

RdStation::events()->conversion([
    'email' => 'example@mail.com',
    'conversion_identifier' => 'identifier',
    'cf_example' => 'An example of custom field', /
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
    
    public function exampleUsingTheAppContainer()
    {
        $rdStation = app()->make(RdStation::class);
        
        $rdStation->events()->conversion([...]);
    }
}

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Lucas Pedroni](https://github.com/pedroni)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
