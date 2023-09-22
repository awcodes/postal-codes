<?php

use Awcodes\PostalCodes\Models\PostalCode;

it('can generate model', function() {
    $code = PostalCode::factory()->create([
        'postal_code' => '31405',
        'state' => 'GA',
    ]);

    expect($code)
        ->postal_code->toBe('31405')
        ->state->toBe('GA');
});
