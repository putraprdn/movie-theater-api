<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie' => new MovieResource($this->movie),
            'hall' => $this->whenLoaded('hall', function () {
                return [
                    'id' => $this->hall->id,
                    'name' => $this->hall->name,
                ];
            }),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
