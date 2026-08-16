---
title: Usage
description: Seed postal code data for a country and query it through the PostalCode Eloquent model.
---

# Usage

## Seeding data

The table is empty after installation. Import a country's data with the seed command:

```bash
php artisan postal-codes:seed
```

The `country` argument defaults to `US`. Pass a different country code to seed somewhere else:

```bash
php artisan postal-codes:seed CA
php artisan postal-codes:seed GB
```

Accepted values are the country codes listed at [download.geonames.org/export/zip](https://download.geonames.org/export/zip/). If GeoNames has no archive for the code you pass, the command reports that it could not download the file and exits without changing the table.

Seeding runs through several steps: it downloads the country archive, extracts it, imports the rows in batches of 1,000, and then deletes the archive and the extracted files. A progress bar reports import progress, which is useful because larger countries contain hundreds of thousands of rows.

> [!WARNING]
> Seeding empties the `postal_codes` table before importing. The table holds one country at a time — seeding `CA` after `US` replaces the American data rather than adding to it.

## Querying

Query the `Awcodes\PostalCodes\Models\PostalCode` model like any other Eloquent model:

```php
use Awcodes\PostalCodes\Models\PostalCode;

$code = PostalCode::query()
    ->where('postal_code', '90210')
    ->first();

$code->place_name; // Beverly Hills
$code->state;      // CA
```

The `postal_code` column is indexed, so lookups by code are fast without further work.

Because a single postal code can cover more than one place name, querying by code may return several rows. Use `get()` rather than `first()` when you need all of them:

```php
$places = PostalCode::query()
    ->where('postal_code', '90210')
    ->get();
```

Find every code in a place:

```php
$codes = PostalCode::query()
    ->where('place_name', 'Beverly Hills')
    ->where('state', 'CA')
    ->pluck('postal_code');
```

`lat` and `lng` are cast to floats, so they can be used directly in distance calculations without converting them first.

### Hidden attributes

The model hides `id`, `created_at`, and `updated_at` when serialized to an array or JSON. Returning a `PostalCode` from an API route therefore yields only the postal data, not the record's bookkeeping columns.

## Column reference

Each row maps to one line of the GeoNames export.

| Column | Type | Description |
| --- | --- | --- |
| `country_code` | string | ISO country code, e.g. `US` |
| `postal_code` | string | The postal code itself. Indexed. |
| `place_name` | string | Place the code covers, e.g. `Beverly Hills` |
| `state_name` | string | First-order administrative division, e.g. `California` |
| `state` | string | Its abbreviation, e.g. `CA` |
| `county_name` | string | Second-order administrative division |
| `county_code` | string | Its code |
| `community_name` | string | Third-order administrative division |
| `community_code` | string | Its code |
| `lat` | float | Latitude |
| `lng` | float | Longitude |
| `accuracy` | integer | Coordinate accuracy, as supplied by GeoNames |

Every column except `postal_code`, `lat`, and `lng` is nullable, and the administrative division fields are populated inconsistently between countries — GeoNames does not supply all of them everywhere. Treat them as optional when writing queries.

## Testing

The package provides a model factory for use in your own tests:

```php
use Awcodes\PostalCodes\Models\PostalCode;

$code = PostalCode::factory()->create([
    'postal_code' => '90210',
    'place_name' => 'Beverly Hills',
]);
```

This avoids seeding real GeoNames data in a test suite, which would be slow and would require network access.
