<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
