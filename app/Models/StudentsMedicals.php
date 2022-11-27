<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsMedicals extends Model
{
    use HasFactory;
    public $keyType = 'string';
    protected $table = 'student_medical';

    protected $fillable = [
        'id',
        'parent_id',
        'children_id',
        'teacher_id',
        'note',
        'status',
        'medical_number',
        'image',
    ];
}
