<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmibHasilTes extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * @var string
     */
    protected $table = 'rmib_hasil_tes';

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     * @var array
     */
    protected $fillable = [
        'karir_data_diri_id',
        'tanggal_pengerjaan',
        'top_1_pekerjaan',
        'top_2_pekerjaan',
        'top_3_pekerjaan',
        'pekerjaan_lain',
        'interpretasi',
    ];

    /**
     * Relasi "belongsTo": Setiap hasil tes dimiliki oleh satu data diri.
     */
    public function dataDiri()
    {
        return $this->belongsTo(KarirDataDiri::class, 'karir_data_diri_id', 'id');
    }

    /**
     * Alias untuk relasi dataDiri (untuk konsistensi dengan konvensi lain)
     */
    public function karirDataDiri()
    {
        return $this->belongsTo(KarirDataDiri::class, 'karir_data_diri_id', 'id');
    }

    /**
     * Relasi "hasMany": Setiap hasil tes memiliki banyak jawaban peserta.
     */
    public function jawaban()
    {
        return $this->hasMany(RmibJawabanPeserta::class, 'hasil_id', 'id');
    }
}