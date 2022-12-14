<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    public $keyType = 'string';

    protected $fillable = [
        'id',
        'course',
        'section',
        'id_number',
        'date_enrolled',
        'total_tuition_fee',
        'year',
    ];

    public function grades()
    {
        return $this->hasMany(Grades::class, 'student_id');
    }

    public function parents()
    {
        return $this->belongsTo(Parents::class, 'id');
    }

    public function users()
    {
        return $this->belongsTo(Users::class, 'parent_id');
    }

    // public function grade() {
    //     return $this->belongsTo(Grades::class, 'student_id');
    // }

}
