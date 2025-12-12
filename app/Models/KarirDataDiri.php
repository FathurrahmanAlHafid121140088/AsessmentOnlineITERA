<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RmibHasilTes;

class KarirDataDiri extends Model
{
    use HasFactory; 

    protected $table = 'karir_data_diri';

    protected $fillable = [
        'nama',
        'nim',
        'jenis_kelamin',
        'alamat',
        'usia',
        'fakultas',
        'program_studi',
        'email',
        'asal_sekolah',
        'provinsi',
        'status_tinggal',
        'prodi_sesuai_keinginan',
        'user_id'
    ];
    public function hasilTes()
    {
        return $this->hasMany(RmibHasilTes::class, 'karir_data_diri_id', 'id');
    }
}