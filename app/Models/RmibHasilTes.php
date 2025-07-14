<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmibHasilTes extends Model
{
    protected $table = 'rmib_hasil_tes';
    protected $primaryKey = 'id_hasil';

    protected $fillable = [
        'user_id',
        'tanggal_pengerjaan',
        'top_1_pekerjaan',
        'top_2_pekerjaan',
        'top_3_pekerjaan',
        'interpretasi',
        'nama',
        'nim',
        'program_studi',
    ];

    public function jawaban()
    {
        return $this->hasMany(RmibJawabanPeserta::class, 'hasil_id', 'id_hasil');
    }
}
