<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        PostalCode::factory()->createMany([
            [
                'postal_code' => '90210',
                'place_name' => 'Beverly Hills',
                'state_name' => 'California',
                'state' => 'CA',
                'lat' => 34.0901,
                'lng' => -118.4065,
            ],
            [
                'postal_code' => '90210',
                'place_name' => 'Beverly Hills PO Boxes',
                'state_name' => 'California',
                'state' => 'CA',
                'lat' => 34.1030,
                'lng' => -118.4105,
            ],
            [
                'postal_code' => '31405',
                'place_name' => 'Savannah',
                'state_name' => 'Georgia',
                'state' => 'GA',
                'lat' => 32.0203,
                'lng' => -81.1188,
            ],
        ]);
    }
}
