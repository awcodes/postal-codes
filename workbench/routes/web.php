<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory | View => view('postal-codes', [
    'postalCodes' => PostalCode::query()
        ->orderBy('postal_code')
        ->orderBy('place_name')
        ->get(),
]));
