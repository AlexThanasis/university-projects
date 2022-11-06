<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => Str::ucfirst(implode(' ', fake()->words(rand(3, 7)))),
            'description' => implode('\n\n', fake()->paragraphs(rand(3, 7))),
            'obtained' => now(),
            'image' => fake()->imageUrl($width=400, $height=400),
        ];
    }
}
