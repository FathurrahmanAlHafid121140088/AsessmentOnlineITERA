<?php

namespace App\Exports;

use App\Models\HasilKuesioner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HasilKuesionerExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    // --- PERBAIKAN DI SINI: Ubah dari protected menjadi public ---
    public $search;
    public $kategori;
    public $sort;
    public $order;
    // --- AKHIR PERBAIKAN ---

    private int $rowNumber = 0;

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
     */
    public function query()
    {
        // ... (Kode query tetap sama) ...
        // Subquery untuk mendapatkan ID hasil kuesioner terakhir per mahasiswa
        $latestIds = DB::table('hasil_kuesioners')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('nim');

        // Query Utama, digabung dengan data_diris untuk sorting & search
        $query = HasilKuesioner::query()
            ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
            ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->select('hasil_kuesioners.*')
            // ✅ TAMBAHAN: Subquery untuk menghitung jumlah tes per mahasiswa
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM hasil_kuesioners as hk_count WHERE hk_count.nim = data_diris.nim) as jumlah_tes'));

        // Terapkan Filter Kategori
        $query->when($this->kategori, function ($q) {
            $q->where('hasil_kuesioners.kategori', $this->kategori);
        });

        // Terapkan Filter Pencarian
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

                        if (in_array(strtolower($term), ['fs', 'fti', 'ftik'])) {
                            $subQuery->orWhere('data_diris.fakultas', strtoupper($term));
                        } else {
                            $subQuery->orWhere('data_diris.fakultas', 'like', "%$term%");
                        }

                        if (in_array(strtolower($term), ['papua'])) {
                            $subQuery->orWhere('data_diris.provinsi', $term);
                        } else {
                            $subQuery->orWhere('data_diris.provinsi', 'like', "%$term%");
                        }

                        if (in_array(strtolower($term), ['fisika', 'arsitektur', 'kimia'])) {
                            $subQuery->orWhere('data_diris.program_studi', $term);
                        } else {
                            $subQuery->orWhere('data_diris.program_studi', 'like', "%$term%");
                        }

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

        return $query->with('dataDiri');
    }

    /**
     * Mendefinisikan judul kolom pada file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Submit',
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
            'Jumlah Tes',
            'Kategori Terakhir',
            'Total Skor Terakhir',
        ];
    }

    /**
     * Memetakan data dari setiap baris query ke format array untuk Excel.
     */
    public function map($hasil): array
    {
        return [
            ++$this->rowNumber,
            $hasil->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s'),
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
            $hasil->jumlah_tes . ' kali', // ✅ PERUBAHAN DI SINI
            $hasil->kategori,
            $hasil->total_skor,
        ];
    }

    /**
     * ✅ 3. Tambahkan fungsi styles() untuk mengatur tampilan spreadsheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Mengatur style untuk baris header (baris ke-1)
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);

        // Mengatur border untuk seluruh data tabel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // Menerapkan style border ke seluruh sel yang berisi data
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray($styleArray);
    }
}
