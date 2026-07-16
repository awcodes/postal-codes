<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Imports\PostalCodeImport;
use Awcodes\PostalCodes\Models\PostalCode;

// A row in GeoNames' tab-delimited export, in documented column order:
// country, postal code, place, state name, state, county name, county code,
// community name, community code, lat, lng, accuracy.
function geonamesRow(): array
{
    return [
        'US', '31405', 'Savannah', 'Georgia', 'GA',
        'Chatham', '051', 'Midtown', '12',
        '32.0203', '-81.1188', '4',
    ];
}

it('maps a geonames row onto the model columns', function () {
    $model = (new PostalCodeImport)->model(geonamesRow());

    expect($model)
        ->toBeInstanceOf(PostalCode::class)
        ->country_code->toBe('US')
        ->postal_code->toBe('31405')
        ->place_name->toBe('Savannah')
        ->state_name->toBe('Georgia')
        ->state->toBe('GA')
        ->county_name->toBe('Chatham')
        ->county_code->toBe('051')
        ->community_name->toBe('Midtown')
        ->community_code->toBe('12')
        ->accuracy->toBe('4');
});

it('maps coordinates to floats via the model casts', function () {
    $model = (new PostalCodeImport)->model(geonamesRow());

    expect($model)
        ->lat->toBeFloat()
        ->lat->toEqualWithDelta(32.0203, 0.0001)
        ->lng->toBeFloat()
        ->lng->toEqualWithDelta(-81.1188, 0.0001);
});

it('builds a model that saves cleanly', function () {
    (new PostalCodeImport)->model(geonamesRow())->save();

    expect(PostalCode::firstWhere('postal_code', '31405'))
        ->not->toBeNull()
        ->place_name->toBe('Savannah');
});

it('tolerates the blank trailing columns geonames emits', function () {
    $row = geonamesRow();
    $row[7] = '';
    $row[8] = '';

    $model = (new PostalCodeImport)->model($row);

    expect($model)
        ->community_name->toBe('')
        ->community_code->toBe('');
});

it('reads the export as tab delimited', function () {
    expect((new PostalCodeImport)->getCsvSettings())
        ->toHaveKey('delimiter', "\t");
});

it('batches inserts', function () {
    expect((new PostalCodeImport)->batchSize())->toBe(1000);
});
