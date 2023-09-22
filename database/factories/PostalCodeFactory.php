<?php

namespace Awcodes\PostalCodes\Database\Factories;

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostalCodeFactory extends Factory
{
    protected $model = PostalCode::class;

    public function definition(): array
    {
        return [
            'country_code' => 'US',
            'postal_code' => rand(10000, 99999),
            'place_name' => $this->faker->city(),
            'state_name' => $this->faker->word(),
            'state' => str($this->faker->randomLetter().$this->faker->randomLetter())->upper(),
            'county_name' => $this->faker->word(),
            'county_code' => rand(100, 999),
            'community_name' => null,
            'community_code' => null,
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'accuracy' => rand(0, 5),
        ];
    }
}
