<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $is_active = (bool)random_int(0, 1);
        $role = ['admin', 'main_admin'];
        $role = $role[array_rand($role)];

        return [
            'email' => fake()->unique()->safeEmail(),
            'role' => $role,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'is_active' => $is_active
        ];
    }
}
