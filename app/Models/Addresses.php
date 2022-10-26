<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_no',
        'barangay',
        'country',
        'province',
        'zip_code',
        'user_id',
    ];
}
