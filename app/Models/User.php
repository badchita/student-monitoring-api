<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    // public $keyType = 'string';
    // public $timestamps = false;
    // protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'username',
        'password',
        'user_type',
        'date_of_birth',
        'date_of_joined',
        'address_id',
        'status',
        'status_verification',
        'children_id',
        'mobile',
        'age',
        'teacher_id',
        'is_email_verified',
    ];

    public function addresses()  {
        return $this->hasMany(Addresses::class, 'user_id');
    }

    public function teachers() {
        return $this->hasMany(Teachers::class, 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
