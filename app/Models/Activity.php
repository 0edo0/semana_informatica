<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_day_id', 'speaker_id', 'title', 'description', 'start_time', 'end_time', 'type'
    ];

    public function scheduleDay()
    {
        return $this->belongsTo(ScheduleDay::class);
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}