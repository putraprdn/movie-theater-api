<?php

namespace App\Http\Controllers\API;

use App\Models\Hall;
use Illuminate\Http\Request;
use App\Http\Resources\HallResource;
use Symfony\Component\HttpFoundation\Response;

class HallController extends baseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $halls = Hall::with(['seats'])->get();

        return $this->sendResponse(HallResource::collection($halls), 'Halls fetched successfully');
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
        $hall = Hall::where('id', $id)->with(['seats'])->first();

        if (is_null($hall)) {
            return $this->sendError('Hall not found.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(new HallResource($hall), 'Hall retrieved successfully');
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
