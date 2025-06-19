<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKeluhans extends Model
{
    protected $table = 'riwayat_keluhans';

    protected $fillable = [
        'nim', 'keluhan', 'lama_keluhan', 'pernah_konsul', 'pernah_tes'
    ];

    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'nim', 'nim');
    }
}
