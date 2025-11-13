<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentalHealthJawabanDetail extends Model
{
    protected $table = 'mental_health_jawaban_details';

    protected $fillable = [
        'hasil_kuesioner_id',
        'nomor_soal',
        'skor'
    ];

    public function hasilKuesioner()
    {
        return $this->belongsTo(HasilKuesioner::class, 'hasil_kuesioner_id');
    }
}
