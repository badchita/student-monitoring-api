<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'subject_id',
        'teacher_id',
        'prelim',
        'midterm',
        'endterm',
        'final_grade',
    ];

    public function students()
    {
        return $this->belongsTo(Students::class, 'id');
    }
}
