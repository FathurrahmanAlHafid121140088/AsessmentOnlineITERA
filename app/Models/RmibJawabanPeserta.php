<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        return $this->belongsTo(RmibHasilTes::class, 'hasil_id', 'id');
    }
}
