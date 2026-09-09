<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Support\Facades\Route;

Route::get('/', fn(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('postal-codes', [
    'postalCodes' => PostalCode::query()
        ->orderBy('postal_code')
        ->orderBy('place_name')
        ->get(),
]));
