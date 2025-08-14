<?php

namespace App\Http\Controllers\API;

use DateTime;
use Carbon\Carbon;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Http\Request;
use App\Http\Resources\ShowtimeResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ShowtimeController extends baseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showtimes = Showtime::with(['hall', 'movie.movie_certification'])->get();

        return $this->sendResponse(ShowtimeResource::collection($showtimes), 'Showtimes fetches successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'movie_id' => ['required', 'exists:movies,id'],
            'hall_id' => ['required', 'exists:halls,id'],
            'start_time' => ['required', 'date_format:Y-m-d H:i',
            // 'min:' . now()->format('Y-m-d H:i')
        ],
            // 'end_time' => [
            //     'nullable',
            //     'date_format:Y-m-d H:i',
            //     function ($attribute, $value, $fail) use ($input) {
            //         if (!empty($value)) {
            //             $start = Carbon::parse($input['start_time']);
            //             $end = Carbon::parse($value);
            //             if ($end->diffInHours($start, false) > 5) {
            //                 $fail('The end time must be within 5 hours after the start time.');
            //             }
            //         }
            //     },
            // ],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Dynamically calculate end_time
        $movieDuration = Movie::where('id', $input['movie_id'])->value('duration');
        $input['end_time'] = Carbon::parse($input['start_time'])
            ->addSeconds($movieDuration)
            ->addMinutes(15)
            ->format('Y-m-d H:i');

        // Prevent duplicates in the same hall overlapping the same date
        $checkDuplicate = Showtime::where('hall_id', $input['hall_id'])
            ->where(function ($query) use ($input) {
                $query->whereBetween('start_time', [$input['start_time'], $input['end_time']])
                    ->orWhereBetween('end_time', [$input['start_time'], $input['end_time']]);
            })
            ->exists();

        if ($checkDuplicate) {
            return $this->sendError('Validation error', [
                'showtime' => 'A showtime already exists in this hall during the specified period.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $showtime = Showtime::create($input);
        $showtime->load(['hall', 'movie.movie_certification']);

        return $this->sendResponse(new ShowtimeResource($showtime), 'Showtime created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $showtime = Showtime::where('id', $id)->with(['hall', 'movie.movie_certification'])->first();

        return $this->sendResponse(new ShowtimeResource($showtime), 'Showtime fetched successfully');
    }

    public function showByHall($id)
    {
        $showtimes = Showtime::where('hall_id', $id)->with(['hall', 'movie.movie_certification'])->get();

        return $this->sendResponse(ShowtimeResource::collection($showtimes), 'Showtimes by hall fetched successfully');
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
