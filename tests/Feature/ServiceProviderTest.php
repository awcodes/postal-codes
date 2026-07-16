<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Schema;

it('runs the package migration', function () {
    expect(Schema::hasTable('postal_codes'))->toBeTrue();
});

it('creates every column the import writes to', function () {
    expect(Schema::hasColumns('postal_codes', [
        'id',
        'country_code',
        'postal_code',
        'place_name',
        'state_name',
        'state',
        'county_name',
        'county_code',
        'community_name',
        'community_code',
        'lat',
        'lng',
        'accuracy',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

it('registers the seeder command', function () {
    expect(app(Kernel::class)->all())
        ->toHaveKey('postal-codes:seed');
});
