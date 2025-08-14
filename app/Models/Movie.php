<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory, CascadeSoftDeletes, SoftDeletes;

    protected $cascadeDeletes = ['showtimes', 'reviews'];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "title",
        "description",
        "release_date",
        "genre_id",
        "certification_id",
        "duration"
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class, "genre_id");
    }

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
