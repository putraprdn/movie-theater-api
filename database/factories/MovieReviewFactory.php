<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieReview>
 */
class MovieReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::inRandomOrder()->first()->id, // Random movie ID
            'user_id' => User::inRandomOrder()->first()->id, // Random user ID
            'rating' => $this->faker->numberBetween(1, 5), // Random rating between 1 and 5
            'review' => $this->faker->paragraph(), // Random review text
        ];
    }
}
