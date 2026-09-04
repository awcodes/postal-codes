# This is my package postal-codes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/awcodes/postal-codes.svg?style=flat-square)](https://packagist.org/packages/awcodes/postal-codes)
[![Total Downloads](https://img.shields.io/packagist/dt/awcodes/postal-codes.svg?style=flat-square)](https://packagist.org/packages/awcodes/postal-codes)

This is a package to easily install and use postal codes in your Laravel application. All data is provided by [GeoNames](https://www.geonames.org/) data dumps. You can see the available countries at [https://download.geonames.org/export/zip/](https://download.geonames.org/export/zip/).

<!-- [docs_start] -->

## Installation & Usage

You can install the package via composer:

```bash
composer require awcodes/postal-codes
```

If you need to publish the migration you may do so with:

```bash
php artisan postal-codes:install
```

Then the only thing left to do is to seed your data. This can be done with the `postal-codes:seed` command.

```bash
php artisan postal-codes:seed {country=US}
```

<!-- [docs_end] -->

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Development

Install dependencies:

    composer install

Run the test suite:

    composer test

Start the Workbench application:

    composer serve

The Workbench is available at [http://localhost:8000](http://localhost:8000) with a small local postal-code dataset. It does not download data from GeoNames unless you explicitly run the seed command.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [awcodes](https://github.com/awcodes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
