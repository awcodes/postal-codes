<?php

namespace Awcodes\PostalCodes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];
}
