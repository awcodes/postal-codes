# Postal Codes

Install a queryable table of postal codes in a Laravel application, seeded from [GeoNames](https://www.geonames.org/) country data.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/awcodes/postal-codes.svg?style=flat-square)](https://packagist.org/packages/awcodes/postal-codes)
[![Total Downloads](https://img.shields.io/packagist/dt/awcodes/postal-codes.svg?style=flat-square)](https://packagist.org/packages/awcodes/postal-codes)

## Documentation

The full documentation lives at **[docs.aw.codes/postal-codes](https://docs.aw.codes/postal-codes/1.x)**.

## Requirements

- PHP 8.3 or higher
- Laravel 12 or higher
- The `zip` PHP extension

## Installation

```bash
composer require awcodes/postal-codes
```

The table is empty until you seed a country's data with `php artisan postal-codes:seed`. See [Usage](https://docs.aw.codes/postal-codes/1.x/usage) for that step.

## Changelog

Please see the [releases](https://github.com/awcodes/postal-codes/releases) for what has changed recently.

## Contributing

Install dependencies with `composer install`, run the test suite with `composer test`, and start the Workbench application with `composer serve`. The Workbench is available at [http://localhost:8000](http://localhost:8000) with a small local postal-code dataset. It does not download data from GeoNames unless you explicitly run the seed command.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [awcodes](https://github.com/awcodes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
