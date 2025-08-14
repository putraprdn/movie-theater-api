<?php

namespace Database\Factories;

use App\Models\Hall;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtime>
 */
class ShowtimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       // Generate random start and end times
       $startTime = $this->faker->dateTimeBetween('now', '+1 weeks'); // Start time within the next 1 weeks
       $endTime = (clone $startTime)->modify('+2 hours'); // End time 2 hours after start time

       return [
           'movie_id' => Movie::inRandomOrder()->first()->id, // Random movie ID
           'hall_id' => Hall::inRandomOrder()->first()->id, // Random hall ID
           'start_time' => $startTime,
           'end_time' => $endTime,
       ];
    }
}
