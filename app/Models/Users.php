<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Jika ada proteksi atau casting bisa ditambahkan di sini, misalnya:
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
