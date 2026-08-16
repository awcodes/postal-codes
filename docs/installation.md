---
title: Installation
description: Install the package with Composer, publish the migration, and prepare the postal codes table.
---

# Installation

## Requirements

- PHP 8.2 or higher
- The `zip` PHP extension

The package depends on [maatwebsite/excel](https://github.com/SpartnerNL/Laravel-Excel) for CSV importing and on [spatie/laravel-package-tools](https://github.com/spatie/laravel-package-tools). Composer installs both for you.

> [!NOTE]
> The `zip` extension is required because the seed command downloads GeoNames data as a ZIP archive and extracts it before importing.

## Install the package

Require the package with Composer:

```bash
composer require awcodes/postal-codes
```

The service provider is registered automatically through Laravel's package discovery. There is nothing to add to `config/app.php` and no configuration file to publish.

## Create the table

The package ships a `create_postal_codes_table` migration and runs it automatically.

If you would rather keep the migration in your own application — to edit it, or to keep the schema visible alongside your other migrations — publish it with the install command:

```bash
php artisan postal-codes:install
```

The command publishes the migration into your `database/migrations` directory and then offers to run your migrations.

If you prefer to run them yourself:

```bash
php artisan migrate
```

## Verify the installation

The table exists once migrations have run, but it is empty until you seed a country. Confirm the package is wired up by checking that the seed command is registered:

```bash
php artisan postal-codes:seed --help
```

With the table in place, continue to [Usage](usage.md) to import data.
