<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'survey_id', 'response'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}