<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDay extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'title'];

    // Relación: Un día tiene muchas actividades
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}