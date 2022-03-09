# Integrate your Laravel project with RD Station

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pedroni/rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/rd-station)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/pedroni/rd-station/run-tests?label=tests)](https://github.com/pedroni/rd-station/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/pedroni/rd-station/Check%20&%20fix%20styling?label=code%20style)](https://github.com/pedroni/rd-station/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pedroni/rd-station.svg?style=flat-square)](https://packagist.org/packages/pedroni/rd-station)

> This is still in development.


## Installation

You can install the package via composer:

```bash
composer require pedroni/rd-station
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="rd-station-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$rdStation = new Pedroni\RdStation();
echo $rdStation->echoPhrase('Hello, Pedroni!');
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

- [pedroni](https://github.com/pedroni)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
