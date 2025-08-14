<?php

namespace App\Http\Controllers\API;

use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Http\Request;
use App\Http\Resources\SeatResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SeatController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seats = Seat::with(['hall'])->get();

        return $this->sendResponse(SeatResource::collection($seats), 'Seats fetched successfully');
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
        $seat = Seat::with(['hall'])->where('id', $id)->first();

        if (is_null($seat)) {
            return $this->sendError('Seat not found.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(new SeatResource($seat), 'Seat retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $seat = Seat::with(['hall'])->where('id', $id)->first();
        // if (is_null($seat)) {
        //     return $this->sendError('Seat not found');
        // }

        // $input = $request->all();

        // $validator = Validator::make($input, [
        //     'hall_id' => ['required', 'exists:halls,id'],
        //     'seat_number' => ['required', 'unique:seats,seat_number,hall_id'],
        // ]);

        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error', $validator->errors());
        // }


        // $seat->hall_id = $input['hall_id'];
        // $seat->seat_number = $input['seat_number'];

        // $seat->save();
        // return $this->sendResponse(new SeatResource($seat), 'Seat updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hall = Hall::destroy($id);
        return $this->sendResponse([], 'Hall successfully deleted');
    }
}
