<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'code',
        'teacher_id',
        'student_id',
        'units'
    ];

    public function teachers()
    {
        return $this->belongsTo(Teachers::class, 'id');
    }

    public function students()
    {
        return $this->belongsTo(Students::class, 'id');
    }
}
