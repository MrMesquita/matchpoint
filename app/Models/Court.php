<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification',
        'capacity',
        'id_arena'
    ];

    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }

    public function courtTimelables()
    {
        return $this->hasMany(CourtTimetables::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
