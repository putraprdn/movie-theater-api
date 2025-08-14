<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        "user_id",
        "showtime_id",
        "booking_time",
        "total_price"
    ];
}
