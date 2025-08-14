<?php

namespace App\Http\Controllers\API;

use App\Models\Seat;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\BookingSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['showtime', 'seats.hall', 'user'])->get();

        return $this->sendResponse(BookingResource::collection($bookings), 'Bookings fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'showtime_id' => ['required', 'exists:showtimes,id'],
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => ['integer', 'distinct']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Fetch allowed seat IDs for that showtime
        $allowedSeatIds = Showtime::where('id', $request['showtime_id'])
            ->with('hall.seats:id,hall_id')
            ->first()
            ->hall
            ->seats
            ->pluck('id');

        // Validate that all requested seats belong to this hall
        $invalidSeats = collect($request['seats'])->reject(fn($id) => $allowedSeatIds->contains($id));
        if (!$invalidSeats->isEmpty()) {
            return $this->sendError('Validation Error', ['seats' => 'Some seats do not belong to this showtime. Troubled seats: ' . $invalidSeats->implode(', ')], Response::HTTP_BAD_REQUEST);
        }

        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) use ($request) {
            $q->where('showtime_id', $request['showtime_id']);
        })
            ->pluck('seat_id');

        $alreadyBooked = collect($request['seats'])->intersect($bookedSeatIds);

        if ($alreadyBooked->isNotEmpty()) {
            return $this->sendError('Validation Error', [
                'seats' => 'Some seats are already booked. Troubled seats: ' . $alreadyBooked->implode(', '),
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {
            $seats = Seat::whereIn('id', $request['seats'])->get(['id', 'price']);

            $totalPrice = $seats->sum('price');

            $booking = Booking::create([
                'user_id'      => Auth::id(),
                'showtime_id'  => $request['showtime_id'],
                'booking_time' => now(),
                'total_price'  => $totalPrice
            ]);

            $booking->seats()->attach($seats->pluck('id')->toArray());

            DB::commit();

            return $this->sendResponse(
                new BookingResource($booking->load(['seats', 'showtime'])),
                'Booking created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->sendError($e, [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $booking = Booking::where('id', $id)->with(['showtime', 'seats.hall', 'user'])->first();

        if (is_null($booking)) {
            return $this->sendError('Booking not found.', [], Response::HTTP_NOT_FOUND);
        }

        if (!(Auth::user()->role->name == 'admin' || $booking->user_id == Auth::id())) {
            return $this->sendError('Unauthorized', [], Response::HTTP_UNAUTHORIZED);
        }

        return $this->sendResponse(new BookingResource($booking), 'Booking retrieved successfully');
    }

    public function showByUser()
    {
        $userId = Auth::user()->id;
        $bookings = Booking::where('user_id', $userId)
            ->where('booking_time', '>=', now()->subWeek())
            ->with(['showtime', 'seats.hall', 'user'])
            ->get();

        return $this->sendResponse(BookingResource::collection($bookings), 'Bookings retrieved successfully');
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
