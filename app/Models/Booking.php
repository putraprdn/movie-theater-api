<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Booking extends Model
{
    use CascadeSoftDeletes, SoftDeletes;

    protected $cascadeDeletes = ['seats'];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "user_id",
        "showtime_id",
        "booking_time",
        "total_price"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class, "showtime_id");
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats');
    }
}
