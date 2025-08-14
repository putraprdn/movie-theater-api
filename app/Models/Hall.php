<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "capacity"
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
