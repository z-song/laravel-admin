<?php

namespace Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Models\Profile;
use Tests\Models\User;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'first_name'	=> $this->faker->firstName,
            'last_name'		=> $this->faker->lastName,
            'postcode'		 => $this->faker->postcode,
            'address'		  => $this->faker->address,
            'latitude'		 => $this->faker->latitude,
            'longitude'		=> $this->faker->longitude,
            'color'			   => $this->faker->hexColor,
            'start_at'		 => $this->faker->dateTime,
            'end_at'		   => $this->faker->dateTime,

			'user_id'		=> User::factory(),
        ];
    }
}
