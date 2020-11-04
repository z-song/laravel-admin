<?php

namespace Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Models\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name'	=> $this->faker->word,
        ];
    }
}
