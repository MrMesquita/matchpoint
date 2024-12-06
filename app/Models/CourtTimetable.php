<?php

namespace App\Models;

use App\Enums\CourtTimetableStatus as EnumsCourtTimetableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtTimetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_court',
        'date',
        'start_time',
        'end_time',
        'status'
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function getStatusAttribute($value)
    {
        return EnumsCourtTimetableStatus::from($value); 
    }

    public function setStatusAttribute(EnumsCourtTimetableStatus $status)
    {
        $this->attributes['status'] = $status->value;
    }
}
