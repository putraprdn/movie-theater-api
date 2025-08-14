<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "description"
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
