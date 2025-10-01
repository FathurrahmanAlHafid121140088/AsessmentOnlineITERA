<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    /**
     * Menggunakan traits bawaan Laravel untuk fungsionalitas tambahan.
     * HasFactory -> Memudahkan pembuatan data dummy untuk testing.
     * Notifiable -> Memungkinkan model ini untuk menerima notifikasi.
     */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Atribut ini mendefinisikan kolom mana saja dari tabel 'users'
     * yang boleh diisi secara massal untuk keamanan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nim',
        'google_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * Atribut ini akan disembunyikan ketika model diubah menjadi array atau JSON.
     * Sangat penting untuk menyembunyikan password dan remember_token.
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
     * Atribut ini secara otomatis mengubah tipe data dari kolom.
     * Misalnya, 'email_verified_at' akan diubah menjadi objek Carbon (tanggal/waktu).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash password saat diset
    ];
}