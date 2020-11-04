<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

$factory = Factory::new();

$factory->define(Tests\Models\User::class, static function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'email'    => $faker->email,
        'mobile'   => $faker->phoneNumber,
        'avatar'   => $faker->imageUrl(),
        'password' => bcrypt('123456'),
    ];
});

$factory->define(Tests\Models\Profile::class, static function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'postcode'   => $faker->postcode,
        'address'    => $faker->address,
        'latitude'   => $faker->latitude,
        'longitude'  => $faker->longitude,
        'color'      => $faker->hexColor,
        'start_at'   => $faker->dateTime,
        'end_at'     => $faker->dateTime,
    ];
});

$factory->define(Tests\Models\Tag::class, static function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
