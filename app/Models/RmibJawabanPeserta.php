<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmibJawabanPeserta extends Model
{
    use HasFactory;

    protected $table = 'rmib_jawaban_peserta';
    protected $fillable = ['user_id', 'jawaban'];

    public function dataDiri()
    {
        return $this->belongsTo(KarirDataDiri::class, 'user_id', 'id');
    }
}
