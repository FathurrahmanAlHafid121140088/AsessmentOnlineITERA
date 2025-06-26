<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiris extends Model
{
    use HasFactory;

    protected $table = 'data_diris';

    protected $primaryKey = 'nim'; // Set primary key jika bukan 'id'
    public $incrementing = false;  // Karena 'nim' bukan auto-increment
    protected $keyType = 'string'; // Karena nim bertipe string

    protected $fillable = [
        'nim', 'nama', 'jenis_kelamin', 'alamat', 'usia', 'fakultas', 'program_studi', 'email'
    ];

    public function riwayatKeluhans()
    {
        return $this->hasMany(RiwayatKeluhans::class, 'nim', 'nim');
    }

    public function hasilKuesioners()
    {
        return $this->hasMany(HasilKuesioner::class, 'nim', 'nim');
    }

    // Relasi ke Jawaban
    public function jawaban()
    {
        return $this->hasOne(Jawaban::class, 'data_diri_id', 'nim');
    }
}
