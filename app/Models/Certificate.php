<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'activity_id', 'payment_id', 'code', 'is_paid', 'is_enabled', 'issued_at'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_enabled' => 'boolean',
        'issued_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}