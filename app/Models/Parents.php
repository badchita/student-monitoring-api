<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;
    public $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
    ];

    public function students()
    {
        return $this->hasMany(Students::class, 'parent_id');
    }
}
