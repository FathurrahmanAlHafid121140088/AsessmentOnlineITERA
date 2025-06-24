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

    // Relasi ke RiwayatKeluhans
    public function riwayatKeluhan()
    {
        return $this->hasOne(RiwayatKeluhans::class, 'nim', 'nim');
    }

    // Relasi ke Jawaban
    public function jawaban()
    {
        return $this->hasOne(Jawaban::class, 'data_diri_id', 'nim');
    }

    // Relasi ke HasilKuesioner
    public function hasilKuesioner()
    {
        return $this->hasOne(HasilKuesioner::class, 'nim', 'nim');
    }
    // app/Models/DataDiris.php

public function scopeSearch($query, $keyword)
{
    return $query->where(function($q) use ($keyword) {
        $q->where('nim', 'like', "%$keyword%")
          ->orWhere('nama', 'like', "%$keyword%")
          ->orWhere('jenis_kelamin', 'like', "%$keyword%")
          ->orWhere('alamat', 'like', "%$keyword%")
          ->orWhere('usia', 'like', "%$keyword%")
          ->orWhere('fakultas', 'like', "%$keyword%")
          ->orWhere('program_studi', 'like', "%$keyword%")
          ->orWhere('email', 'like', "%$keyword%");
    })->orWhereHas('riwayatKeluhan', function($q) use ($keyword) {
        $q->where('keluhan', 'like', "%$keyword%")
          ->orWhere('lama_keluhan', 'like', "%$keyword%")
          ->orWhere('pernah_konsul', 'like', "%$keyword%")
          ->orWhere('pernah_tes', 'like', "%$keyword%");
    });
}
}
