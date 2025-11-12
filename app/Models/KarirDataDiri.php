<?php

// app/Models/KarirDataDiri.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika belum ada
use App\Models\RmibHasilTes;

class KarirDataDiri extends Model
{
    use HasFactory; // Tambahkan ini jika belum ada

    protected $table = 'karir_data_diri';
    
    // ==========================================================
    // == HAPUS 3 BARIS DI BAWAH INI
    // ==========================================================
    // protected $primaryKey = 'nim';
    // public $incrementing = false;
    // protected $keyType = 'string';
    // ==========================================================

    protected $fillable = [
        'nama',
        'nim',
        'jenis_kelamin',
        'alamat',
        'usia',
        'fakultas',
        'program_studi',
        'email',
        'asal_sekolah',
        'provinsi',
        'status_tinggal',
        'prodi_sesuai_keinginan',
        'user_id' // Jika Anda memutuskan untuk menggunakannya kembali
    ];

    /**
     * PERBAIKI FUNGSI INI
     * Satu data diri bisa memiliki BANYAK hasil tes (jika ada tes ulang).
     * Relasi ini menghubungkan 'id' di tabel ini ke 'karir_data_diri_id' di tabel rmib_hasil_tes.
     */
    public function hasilTes()
    {
        return $this->hasMany(RmibHasilTes::class, 'karir_data_diri_id', 'id');
    }
}