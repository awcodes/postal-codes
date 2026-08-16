---
title: Postal Codes
description: Install a queryable table of postal codes in a Laravel application, seeded from GeoNames country data.
---

# Postal Codes

Postal Codes gives a Laravel application a local `postal_codes` table populated from [GeoNames](https://www.geonames.org/) country data, along with an Eloquent model to query it.

Rather than calling a geocoding API at request time, you seed the data once with an Artisan command and query it like any other table. Lookups are ordinary database queries, so they work offline, cost nothing per call, and can be joined against your own records.

## What you get

Installing the package adds three things to your application:

- A `postal_codes` table, created by a migration the package publishes.
- An `Awcodes\PostalCodes\Models\PostalCode` Eloquent model.
- A `postal-codes:seed` command that downloads and imports a country's data.

Each row carries the postal code itself plus its place name, administrative divisions, and latitude and longitude. See [Usage](usage.md) for the full column reference.

## Where the data comes from

All data comes from the GeoNames postal code export at [download.geonames.org/export/zip](https://download.geonames.org/export/zip/), which publishes one archive per country.

The seed command downloads the archive for the country you name, imports it, and cleans up after itself. Nothing is bundled with the package, so the download directory is also the definitive list of which countries are available.

## Next steps

Start with [Installation](installation.md), then seed your first country in [Usage](usage.md).
