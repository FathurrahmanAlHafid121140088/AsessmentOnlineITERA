<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabansDetails extends Model
{
    protected $table = 'jawabans_details';

    protected $fillable = [
        'jawaban_id', 'nomor_soal', 'skor'
    ];

    public function jawaban()
    {
        return $this->belongsTo(Jawaban::class, 'jawaban_id');
    }
}


