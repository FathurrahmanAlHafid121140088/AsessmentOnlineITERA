<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmibPekerjaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel database yang terhubung dengan model ini.
     * PASTIKAN NAMA INI BENAR SESUAI DATABASE ANDA.
     * @var string
     */
    protected $table = 'rmib_pekerjaan';

    /**
     * Atribut yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'kelompok',
        'gender',
        'nama_pekerjaan',
    ];
}