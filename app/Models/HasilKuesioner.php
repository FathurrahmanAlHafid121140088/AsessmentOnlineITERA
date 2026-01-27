<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKuesioner extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuesioners';

    // WAJIB UPDATE BAGIAN INI AGAR TIDAK MENTAL (REFRESH)
    protected $fillable = [
        'nim',
        'total_skor',
        'kategori',
        'tanggal_pengerjaan',
        'posisi_soal_terakhir', // <--- Kolom Baru
        'status',               // <--- Kolom Baru
        'draft_jawaban'         // <--- Kolom Baru
    ];

    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'nim', 'nim');
    }

    public function jawabanDetails()
    {
        return $this->hasMany(MentalHealthJawabanDetail::class, 'hasil_kuesioner_id');
    }

    public function riwayatKeluhans()
    {
        return $this->hasMany(RiwayatKeluhans::class, 'nim', 'nim');
    }
}