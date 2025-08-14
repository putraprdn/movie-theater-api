<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\MovieCertification;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\MovieCertificationResource;

class MovieCertificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certifications = MovieCertification::all();

        return $this->sendResponse(MovieCertificationResource::collection($certifications), "Movie certifications fetched successfully");
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
        // $certification = MovieCertification::where('id', $id)->first();

        // if (is_null($certification)) {
        //     return $this->sendError('Movie certification not found.', [], Response::HTTP_NOT_FOUND);
        // }

        // return $this->sendResponse(new MovieCertificationResource($certification), 'Movie certification retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovieCertification $movieCertification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovieCertification $movieCertification)
    {
        //
    }
}
