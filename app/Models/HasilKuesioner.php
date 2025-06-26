<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HasilKuesioner extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuesioners';

    protected $fillable = [
        'nim', 'total_skor', 'kategori', 'created_at', 'updated_at'
    ];

    // Relasi ke DataDiris
    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'nim', 'nim');
    }
    // App\Models\HasilKuesioner.php
    public function riwayatKeluhans()
    {
        return $this->hasMany(RiwayatKeluhans::class, 'nim', 'nim');
    }
}
