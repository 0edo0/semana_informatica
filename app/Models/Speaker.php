<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    // Campos que permitimos guardar desde formularios
    protected $fillable = [
        'name', 
        'profession', 
        'bio', 
        'photo_path'
    ];

    // Relación: Un ponente dicta muchas actividades
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}