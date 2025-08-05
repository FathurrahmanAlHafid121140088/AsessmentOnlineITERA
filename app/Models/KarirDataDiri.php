<?php

// KarirDataDiri.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KarirDataDiri extends Model
{
    protected $table = 'karir_data_diri';
    
    protected $fillable = [
        'nama',
        'nim', 
        'jenis_kelamin',
        'alamat',
        'usia',
        'fakultas',
        'program_studi',
        'email'
    ];

    // Relasi ke hasil tes
    public function hasilTes()
    {
        return $this->hasOne(RmibHasilTes::class, 'user_id', 'id');
    }
}

// RmibHasilTes.php - Updated
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmibHasilTes extends Model
{
    protected $table = 'rmib_hasil_tes';
    protected $primaryKey = 'id_hasil';

    protected $fillable = [
        'user_id', // foreign key ke karir_data_diri
        'tanggal_pengerjaan',
        'top_1_pekerjaan',
        'top_2_pekerjaan',
        'top_3_pekerjaan',
        'interpretasi',
        'nama',
        'nim',
        'program_studi',
    ];

    protected $casts = [
        'tanggal_pengerjaan' => 'datetime'
    ];

    public function jawaban()
    {
        return $this->hasMany(RmibJawabanPeserta::class, 'hasil_id', 'id_hasil');
    }

    public function dataDiri()
    {
        return $this->belongsTo(KarirDataDiri::class, 'user_id', 'id');
    }
}

// RmibJawabanPeserta.php - Updated
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmibJawabanPeserta extends Model
{
    protected $table = 'rmib_jawaban_peserta';

    protected $fillable = [
        'hasil_id', 
        'kelompok', 
        'pekerjaan', 
        'peringkat'
    ];

    public function hasil()
    {
        return $this->belongsTo(RmibHasilTes::class, 'hasil_id', 'id_hasil');
    }
}