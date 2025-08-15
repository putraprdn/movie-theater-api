<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HallController;
use App\Http\Controllers\API\SeatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ShowtimeController;
use App\Http\Controllers\API\MovieCertificationController;

Route::controller(UserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {

    // Accessible by all authenticated users
    Route::get('movies', [MovieController::class, 'index']);
    Route::get('movies/{id}', [MovieController::class, 'show']);

    Route::get('genres', [GenreController::class, 'index']);
    Route::get('genres/{id}', [GenreController::class, 'show']);

    Route::get('bookings/me', [BookingController::class, 'showByUser']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::post('bookings', [BookingController::class, 'store']);

    Route::get('seats', [SeatController::class, 'index']);
    Route::get('seats/{id}', [SeatController::class, 'index']);

    Route::get('halls', [HallController::class, 'index']);
    Route::get('halls/{id}', [HallController::class, 'show']);

    Route::get('showtimes', [ShowtimeController::class, 'index']);
    Route::get('showtimes/halls/{id}', [ShowtimeController::class, 'showByHall']);
    Route::get('showtimes/{id}', [ShowtimeController::class, 'show']);
    Route::get('showtimes/{id}/seats', [ShowtimeController::class, 'showAvailableSeats']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::post('movies', [MovieController::class, 'store']);
        Route::put('movies/{id}', [MovieController::class, 'update']);
        Route::delete('movies/{id}', [MovieController::class, 'destroy']);

        Route::post('genres', [GenreController::class, 'store']);
        Route::put('genres/{id}', [GenreController::class, 'update']);

        Route::get('bookings', [BookingController::class, 'index']);

        Route::post('showtimes', [ShowtimeController::class, 'store']);

        Route::resource('movie-certifications', MovieCertificationController::class);
    });


    // Route::resource('movie-certifications', MovieCertificationController::class);
    // Route::resource('genres', GenreController::class)->except(['index', 'show']);
    // Route::resource('showtimes', ShowtimeController::class);

    // Route::get('bookings/me', [BookingController::class, 'showByUser']);
    // Route::resource('bookings', BookingController::class);

    // Route::get('seats/hall/{id}', [SeatController::class, 'showByHallId']);
    // Route::resource('seats', SeatController::class);
});
