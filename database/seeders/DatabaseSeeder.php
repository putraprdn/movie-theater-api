<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Role;
use App\Models\Seat;
use App\Models\User;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\MovieReview;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MovieCertification;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Roles
        $adminRole = Role::factory()->create(['name' => 'admin']);
        $userRole = Role::factory()->create(['name' => 'user']);

        // Users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        Genre::factory(5)->create();

        MovieCertification::insert([
            ['code' => 'G', 'description' => 'General audiences. All ages are admitted.'],
            ['code' => 'PG', 'description' => 'Parental guidance suggested. Some material may not be suitable for children.'],
            ['code' => 'PG-13', 'description' => 'Parents are strongly cautioned. Some material may be inappropriate for children under 13.'],
            ['code' => 'R', 'description' => 'Restricted. Parents are strongly cautioned. Some material may be inappropriate for children under 17.'],
            ['code' => 'NC-17', 'description' => 'No one 17 and under admitted.'],
        ]);

        Movie::factory(10)->create();

        MovieReview::factory(5)->create();

        $halls = Hall::factory(3)->create();

        $rows = ['A', 'B', 'C', 'D', 'E'];
        $seatsPerRow = 10;

        foreach ($halls as $hall) {
            $seats = [];

            foreach ($rows as $row) {
                for ($i = 1; $i <= $seatsPerRow; $i++) {
                    $seats[] = [
                        'hall_id' => $hall->id,
                        'seat_number' => $row . $i,
                    ];
                }
            }

            Seat::insert($seats);
        }

        Showtime::factory(10)->create();
    }
}
