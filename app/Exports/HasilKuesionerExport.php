<?php

namespace App\Exports;

use App\Models\HasilKuesioner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HasilKuesionerExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $kategori;
    protected $sort;
    protected $order;

    /**
     * Menerima parameter filter dari controller.
     */
    public function __construct($search, $kategori, $sort, $order)
    {
        $this->search = $search;
        $this->kategori = $kategori;
        $this->sort = $sort ?: 'created_at';
        $this->order = $order ?: 'desc';
    }

    /**
     * Menyiapkan query builder untuk mengambil data yang akan diekspor.
     * Logika query ini sekarang identik dengan yang ada di controller.
     */
    public function query()
    {
        // Subquery untuk mendapatkan ID hasil kuesioner terakhir per mahasiswa
        $latestIds = DB::table('hasil_kuesioners')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('nim');

        // Query Utama, digabung dengan data_diris untuk sorting & search
        $query = HasilKuesioner::query()
            ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
            ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->select('hasil_kuesioners.*'); // Eager load relasi untuk efisiensi di method map()

        // Terapkan Filter Kategori
        $query->when($this->kategori, function ($q) {
            $q->where('hasil_kuesioners.kategori', $this->kategori);
        });

        // Terapkan Filter Pencarian (LOGIKA BARU YANG LEBIH DETAIL)
        $query->when($this->search, function ($q) {
            $terms = array_filter(preg_split('/\s+/', trim($this->search)));
            $q->where(function (Builder $query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where(function (Builder $subQuery) use ($term) {
                        $subQuery->orWhere('hasil_kuesioners.nim', 'like', "%$term%")
                            ->orWhere('data_diris.nama', 'like', "%$term%")
                            ->orWhere('data_diris.email', 'like', "%$term%")
                            ->orWhere('data_diris.alamat', 'like', "%$term%")
                            ->orWhere('data_diris.asal_sekolah', 'like', "%$term%")
                            ->orWhere('data_diris.status_tinggal', 'like', "%$term%");

                        // Logika pencarian khusus untuk fakultas (exact match)
                        if (in_array(strtolower($term), ['fs', 'fti', 'ftik'])) {
                            $subQuery->orWhere('data_diris.fakultas', strtoupper($term));
                        } else {
                            $subQuery->orWhere('data_diris.fakultas', 'like', "%$term%");
                        }

                        // Logika pencarian khusus untuk provinsi (exact match)
                        if (in_array(strtolower($term), ['papua'])) {
                            $subQuery->orWhere('data_diris.provinsi', $term);
                        } else {
                            $subQuery->orWhere('data_diris.provinsi', 'like', "%$term%");
                        }

                        // Logika pencarian khusus untuk program studi (exact match)
                        if (in_array(strtolower($term), ['fisika', 'arsitektur', 'kimia'])) {
                            $subQuery->orWhere('data_diris.program_studi', $term);
                        } else {
                            $subQuery->orWhere('data_diris.program_studi', 'like', "%$term%");
                        }

                        // Logika pencarian khusus untuk jenis kelamin (exact match)
                        if (in_array(strtolower($term), ['l', 'p'])) {
                            $subQuery->orWhere('data_diris.jenis_kelamin', strtoupper($term));
                        }
                    });
                }
            });
        });

        // Terapkan Sorting
        $sortColumn = match ($this->sort) {
            'nama' => 'data_diris.nama',
            default => 'hasil_kuesioners.' . $this->sort,
        };
        $query->orderBy($sortColumn, $this->order);

        // Load relasi dataDiri untuk digunakan di method map()
        return $query->with('dataDiri');
    }

    /**
     * Mendefinisikan judul kolom pada file Excel.
     */
    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Fakultas',
            'Program Studi',
            'Jenis Kelamin',
            'Usia',
            'Provinsi',
            'Alamat',
            'Email',
            'Asal Sekolah',
            'Status Tinggal',
            'Kategori Terakhir',
            'Total Skor Terakhir',
            'Tanggal Submit',
        ];
    }

    /**
     * Memetakan data dari setiap baris query ke format array untuk Excel.
     */
    public function map($hasil): array
    {
        return [
            $hasil->nim,
            $hasil->dataDiri->nama ?? 'N/A',
            $hasil->dataDiri->fakultas ?? 'N/A',
            $hasil->dataDiri->program_studi ?? 'N/A',
            $hasil->dataDiri->jenis_kelamin ?? 'N/A',
            $hasil->dataDiri->usia ?? 'N/A',
            $hasil->dataDiri->provinsi ?? 'N/A',
            $hasil->dataDiri->alamat ?? 'N/A',
            $hasil->dataDiri->email ?? 'N/A',
            $hasil->dataDiri->asal_sekolah ?? 'N/A',
            $hasil->dataDiri->status_tinggal ?? 'N/A',
            $hasil->kategori,
            $hasil->total_skor,
            $hasil->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s'),
        ];
    }
}

