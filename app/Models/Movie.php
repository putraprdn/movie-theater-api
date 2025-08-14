<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "release_date",
        "genre_id",
        "certification_id",
    ];

    public function movie_certification()
    {
        return $this->belongsTo(MovieCertification::class, "certification_id");
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function reviews()
    {
        return $this->hasMany(MovieReview::class);
    }
}
