<?php

namespace App\Models;

// 1. Pastikan Anda mengimpor HasFactory
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    // 2. Pastikan Anda menggunakan HasFactory
    use HasFactory, Notifiable;

    /**
     * Tentukan 'nim' sebagai Primary Key.
     * Ini sangat penting karena kode Anda tidak menggunakan 'id'.
     */
    protected $primaryKey = 'nim';

    /**
     * Beritahu Laravel bahwa 'nim' bukan auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Tentukan tipe data 'nim' (bisa string atau integer).
     * Jika NIM Anda bisa diawali angka 0, gunakan 'string'.
     */
    protected $keyType = 'string';

    /**
     * Atribut yang boleh diisi secara massal.
     * Disesuaikan dengan kode AuthController Anda.
     */
    protected $fillable = [
        'nim',
        'name',
        'email',
        'password',
        'google_id',
    ];

    /**
     * Atribut yang harus disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
