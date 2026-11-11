<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'profession', 'bio', 'photo_path'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}