<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('postal-codes', [
        'postalCodes' => PostalCode::query()
            ->orderBy('postal_code')
            ->orderBy('place_name')
            ->get(),
    ]);
});
