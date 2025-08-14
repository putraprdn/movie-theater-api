<?php

namespace App\Http\Controllers\API;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Resources\GenreResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::with(['movies.movie_certification'])->get();

        return $this->sendResponse(GenreResource::collection($genres), 'Genres fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $genre = Genre::create($input);

        return $this->sendResponse(new GenreResource($genre), 'Genre created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $genre = Genre::where('id', $id)->with(['movies.movie_certification'])->first();

        if (is_null($genre)) {
            return $this->sendError('Genre not found.', [], Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(new GenreResource($genre), 'Genre retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::where('id', $id)->first();
        if (is_null($genre)) {
            return $this->sendError('Genre not found');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }


        $genre->name = $input['name'];

        $genre->save();
        return $this->sendResponse(new GenreResource($genre), 'Genre updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        //
    }
}
