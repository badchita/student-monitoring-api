<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    use HasFactory;
    public $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'date_hired',
        'id_number',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class, 'teacher_id');
    }
}
