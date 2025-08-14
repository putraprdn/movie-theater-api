<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\MovieCertification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'release_date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(4800, 10800), // Duration in seconds
            'genre_id' => Genre::inRandomOrder()->first()->id, // Random genre
            'certification_id' => MovieCertification::inRandomOrder()->first()->id, // Random certification
        ];
    }
}
