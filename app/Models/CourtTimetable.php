<?php

namespace App\Models;

use App\Enums\CourtTimetableStatus as EnumsCourtTimetableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtTimetable extends Model
{
    use HasFactory;

    protected $table = "court_timetables";

    protected $fillable = [
        'court_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status'
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getStatusAttribute($value)
    {
        return EnumsCourtTimetableStatus::from($value); 
    }

    public function setStatusAttribute($status)
    {
        if (is_string($status)) {
            $status = EnumsCourtTimetableStatus::from($status);
        }
    
        $this->attributes['status'] = $status->value;
    }

    public function existsConflictingTimetable($courtId, $date, $endTime, $startTime)
    {
        return $this::where('court_id', $courtId)
        ->where('day_of_week', $date)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            });
        })->exists();   
    }
}
