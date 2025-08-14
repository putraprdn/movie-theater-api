<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovieReview extends Model
{
    use HasFactory;

    protected $fillable = [
        "movie_id",
        "user_id",
        "rating",
        "review"
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, "movie_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
