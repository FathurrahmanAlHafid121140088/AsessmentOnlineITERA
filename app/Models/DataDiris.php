<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiris extends Model
{
    use HasFactory;

    protected $table = 'data_diris';

    protected $primaryKey = 'nim'; // Set primary key jika bukan 'id'
    public $incrementing = false;  // Karena 'nim' bukan auto-increment
    protected $keyType = 'string'; // Karena nim bertipe string

    protected $fillable = [
        'nim',
        'nama',
        'jenis_kelamin',
        'provinsi',       // kolom baru
        'alamat',
        'usia',
        'fakultas',
        'program_studi',
        'asal_sekolah',   // kolom baru
        'status_tinggal', // kolom baru
        'email'
    ];

    // --- RELASI-RELASI MODEL ---

    public function riwayatKeluhans()
    {
        return $this->hasMany(RiwayatKeluhans::class, 'nim', 'nim');
    }

    public function hasilKuesioners()
    {
        return $this->hasMany(HasilKuesioner::class, 'nim', 'nim');
    }

    // Hasil kuesioner terbaru
    public function latestHasilKuesioner()
    {
        return $this->hasOne(HasilKuesioner::class, 'nim', 'nim')->latestOfMany();
    }

    public function jawaban()
    {
        return $this->hasOne(Jawaban::class, 'data_diri_id', 'nim');
    }

    // --- SCOPE UNTUK QUERY ---

    /**
     * Scope pencarian yang dioptimalkan menggunakan JOIN.
     * Ini jauh lebih cepat daripada `orWhereHas`.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        // Gunakan LEFT JOIN agar data diri tetap tampil meski belum ada riwayat/hasil
        return $query
            ->select('data_diris.*') // Pilih kolom dari tabel utama
            ->distinct() // Hindari duplikasi hasil karena join
            ->leftJoin('riwayat_keluhans', 'data_diris.nim', '=', 'riwayat_keluhans.nim')
            ->leftJoin('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
            ->where(function ($q) use ($keyword) {
                // Pencarian pada tabel data_diris
                $q->where('data_diris.nim', 'like', "%$keyword%")
                    ->orWhere('data_diris.nama', 'like', "%$keyword%")
                    ->orWhere('data_diris.jenis_kelamin', 'like', "%$keyword%")
                    ->orWhere('data_diris.provinsi', 'like', "%$keyword%")
                    ->orWhere('data_diris.alamat', 'like', "%$keyword%")
                    ->orWhere('data_diris.usia', 'like', "%$keyword%")
                    ->orWhere('data_diris.fakultas', 'like', "%$keyword%")
                    ->orWhere('data_diris.program_studi', 'like', "%$keyword%")
                    ->orWhere('data_diris.asal_sekolah', 'like', "%$keyword%")
                    ->orWhere('data_diris.status_tinggal', 'like', "%$keyword%")
                    ->orWhere('data_diris.email', 'like', "%$keyword%")

                    // Pencarian pada tabel riwayat_keluhans (setelah di-JOIN)
                    ->orWhere('riwayat_keluhans.keluhan', 'like', "%$keyword%")
                    ->orWhere('riwayat_keluhans.lama_keluhan', 'like', "%$keyword%")
                    ->orWhere('riwayat_keluhans.pernah_konsul', 'like', "%$keyword%")
                    ->orWhere('riwayat_keluhans.pernah_tes', 'like', "%$keyword%")

                    // Pencarian pada tabel hasil_kuesioners (setelah di-JOIN)
                    ->orWhere('hasil_kuesioners.total_skor', 'like', "%$keyword%")
                    ->orWhere('hasil_kuesioners.kategori', 'like', "%$keyword%")
                    ->orWhere('hasil_kuesioners.created_at', 'like', "%$keyword%");
            });
    }
}

