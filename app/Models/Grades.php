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
        'student_status',
        'absent'
    ];

    public function students()
    {
        return $this->belongsTo(Students::class, 'id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subjects::class, 'id');
    }

    // public function student()
    // {
    //     return $this->hasMany(Students::class, 'id');
    // }
}
