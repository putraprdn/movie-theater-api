<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory, CascadeSoftDeletes, SoftDeletes;

    protected $cascadeDeletes = ['bookings'];

    protected $dates = ['deleted_at'];


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

    public function hall()
    {
        return $this->belongsTo(Hall::class, "hall_id");
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
