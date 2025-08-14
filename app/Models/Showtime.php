<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        "movie_id",
        "hall_id",
        "start_time",
        "end_time"
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, "movie_id");
    }
}
