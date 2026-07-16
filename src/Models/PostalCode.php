<?php

declare(strict_types=1);

namespace Awcodes\PostalCodes\Models;

use Awcodes\PostalCodes\Database\Factories\PostalCodeFactory;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $country_code
 * @property-read string $postal_code
 * @property-read string $place_name
 * @property-read string $state_name
 * @property-read string $state
 * @property-read string $county_name
 * @property-read string $county_code
 * @property-read string $community_name
 * @property-read string $community_code
 * @property-read float $lat
 * @property-read float $lng
 * @property-read int $accuracy
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
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

    protected static function newFactory(): PostalCodeFactory
    {
        return new PostalCodeFactory;
    }
}
