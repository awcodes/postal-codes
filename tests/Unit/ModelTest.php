<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Database\Factories\PostalCodeFactory;
use Awcodes\PostalCodes\Models\PostalCode;

it('can generate model', function () {
    $code = PostalCode::factory()->create([
        'postal_code' => '31405',
        'state' => 'GA',
    ]);

    expect($code)
        ->postal_code->toBe('31405')
        ->state->toBe('GA');
});

it('resolves its own factory', function () {
    expect(PostalCode::factory())->toBeInstanceOf(PostalCodeFactory::class);
});

it('persists every fillable attribute', function () {
    PostalCode::create([
        'country_code' => 'US',
        'postal_code' => '31405',
        'place_name' => 'Savannah',
        'state_name' => 'Georgia',
        'state' => 'GA',
        'county_name' => 'Chatham',
        'county_code' => '051',
        'community_name' => 'Midtown',
        'community_code' => '12',
        'lat' => 32.0203,
        'lng' => -81.1188,
        'accuracy' => 4,
    ]);

    expect(PostalCode::firstWhere('postal_code', '31405'))
        ->country_code->toBe('US')
        ->place_name->toBe('Savannah')
        ->state_name->toBe('Georgia')
        ->state->toBe('GA')
        ->county_name->toBe('Chatham')
        ->county_code->toBe('051')
        ->community_name->toBe('Midtown')
        ->community_code->toBe('12')
        ->accuracy->toBe(4);
});

it('casts coordinates to floats', function () {
    $code = PostalCode::factory()->create([
        'lat' => '32.0203',
        'lng' => '-81.1188',
    ]);

    expect($code->fresh())
        ->lat->toBeFloat()
        ->lat->toEqualWithDelta(32.0203, 0.0001)
        ->lng->toBeFloat()
        ->lng->toEqualWithDelta(-81.1188, 0.0001);
});

it('hides internal attributes from serialization', function () {
    $array = PostalCode::factory()->create()->toArray();

    expect($array)
        ->not->toHaveKeys(['id', 'created_at', 'updated_at'])
        ->toHaveKeys(['country_code', 'postal_code', 'place_name']);
});

it('does not mass assign the primary key', function () {
    $code = PostalCode::factory()->create();

    $code->fill(['id' => 9999]);

    expect($code->id)->not->toBe(9999);
});
