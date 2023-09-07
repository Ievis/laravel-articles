<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public int $number = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $is_active = (bool)random_int(0, 1);

        return [
            'name' => fake()->unique()->word,
            'is_active' => $is_active,
            'number' => $this->number++
        ];
    }
}
