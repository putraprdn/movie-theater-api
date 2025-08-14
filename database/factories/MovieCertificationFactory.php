<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieCertification>
 */
class MovieCertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'description' => $this->faker->sentence(),
        ];
    }
}
