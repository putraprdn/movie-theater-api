<?php

namespace App\Http\Controllers\API;

use App\Models\Hall;
use App\Models\Showtime;
use Illuminate\Http\Request;
use App\Http\Resources\ShowtimeResource;
use App\Models\Booking;

class ShowtimeController extends baseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showtimes = Showtime::with(['hall', 'movie'])->get();

        return $this->sendResponse(ShowtimeResource::collection($showtimes), 'Showtimes fetches successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $showtime = Showtime::where('id', $id)->with(['hall', 'movie'])->first();

        return $this->sendResponse(new ShowtimeResource($showtime), 'Showtime fetched successfully');
    }

    public function showAvailableSeats($id)
    {
        $hallId = Showtime::where('id', $id)
            ->with(['hall:id'])
            ->first()
            ->hall
            ->id ?? null;

        if (!$hallId) {
            return $this->sendError('Showtime or hall not found', []);
        }

        // Get all seat IDs for the hall
        $allSeats = Hall::where('id', $hallId)
            ->with(['seats:id,hall_id'])
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id');

        // Get booked seat IDs for the showtime
        $bookedSeats = Booking::where('showtime_id', $id)
            ->with(['seats:id'])
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id');

        // Find available seats by excluding booked seats from all seats
        $availableSeats = $allSeats->diff($bookedSeats)->sort()->values();

        return $this->sendResponse(
            ['available_seat_ids' => $availableSeats],
            'Available seats fetched successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
