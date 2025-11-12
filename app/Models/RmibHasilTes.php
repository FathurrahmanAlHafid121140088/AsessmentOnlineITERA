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
        'top_1_alasan',
        'top_2_pekerjaan',
        'top_2_alasan',
        'top_3_pekerjaan',
        'top_3_alasan',
        'pekerjaan_lain',
        'pekerjaan_lain_alasan',
        'skor_konsistensi',
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
}