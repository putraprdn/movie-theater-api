<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hall extends Model
{
    use HasFactory, CascadeSoftDeletes, SoftDeletes;

    protected $cascadeDeletes = ['showtimes', 'seats'];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "name",
        "capacity"
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
