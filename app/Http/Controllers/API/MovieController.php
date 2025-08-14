<?php

namespace App\Http\Controllers\API;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MovieController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::with(['genre', 'movie_certification'])->get();

        return $this->sendResponse(MovieResource::collection($movies), 'Movies fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'duration' => ['nullable'],
            'release_date' => ['nullable', 'date_format:Y-m-d'],
            'genre_id' => ['required', 'exists:genres,id'],
            'certification_id' => ['required', 'exists:movie_certifications,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $movie = Movie::create($input);
        $movie->load(['genre', 'movie_certification']);

        return $this->sendResponse(new MovieResource($movie), 'Movie created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movie = Movie::where('id', $id)->with(['genre', 'movie_certification'])->first();

        if (is_null($movie)) {
            return $this->sendError('Movie not found.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(new MovieResource($movie), 'Movie retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::where('id', $id)->with(['genre', 'movie_certification'])->first();
        if (is_null($movie)) {
            return $this->sendError('Movie not found');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'duration' => ['nullable'],
            'release_date' => ['nullable', 'date_format:Y-m-d'],
            'genre_id' => ['required', 'exists:genres,id'],
            'certification_id' => ['required', 'exists:movie_certifications,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }


        $movie->title = $input['title'];
        $movie->description = $input['description'] ?? null;
        $movie->duration = $input['duration'] ?? null;
        $movie->release_date = $input['release_date'] ?? null;
        $movie->genre_id = $input['genre_id'];
        $movie->certification_id = $input['certification_id'];

        $movie->save();
        return $this->sendResponse(new MovieResource($movie), 'Movie updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movie = Movie::where('id', $id)->first();
        if (is_null($movie)) {
            return $this->sendError('Movie is not found', []);
        }

        $movie->delete();
        return $this->sendResponse([], 'Movie deleted successfully');
    }
}
