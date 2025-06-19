<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = 'jawaban';

    protected $fillable = [
        'data_diri_id', 'total_skor'
    ];

    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'data_diri_id');
    }

    public function details()
    {
        return $this->hasMany(JawabansDetails::class, 'jawaban_id');
    }
}
