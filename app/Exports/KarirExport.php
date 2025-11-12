<?php

namespace App\Exports;

use App\Models\RmibHasilTes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KarirExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $hasilTes;
    private int $rowNumber = 0;

    /**
     * Menerima collection hasil tes dari controller
     */
    public function __construct($hasilTes)
    {
        $this->hasilTes = $hasilTes;
    }

    /**
     * Mengambil collection data yang akan diekspor
     */
    public function collection()
    {
        return $this->hasilTes;
    }

    /**
     * Mendefinisikan judul kolom pada file Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Tes',
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
            'Prodi Sesuai Keinginan',
            'Top 1 Pekerjaan',
            'Top 2 Pekerjaan',
            'Top 3 Pekerjaan',
            'Interpretasi',
        ];
    }

    /**
     * Memetakan data dari setiap baris ke format array untuk Excel
     */
    public function map($hasil): array
    {
        // Mapping kategori RMIB ke format lengkap
        $kategoriRMIB = [
            'Outdoor' => 'Outdoor (O)',
            'Mechanical' => 'Mechanical (M)',
            'Computational' => 'Computational (C)',
            'Scientific' => 'Scientific (S)',
            'Personal Contact' => 'Personal Contact (P)',
            'Aesthetic' => 'Aesthetic (A)',
            'Literary' => 'Literary (L)',
            'Musical' => 'Musical (Mu)',
            'Social Service' => 'Social Service (SS)',
            'Clerical' => 'Clerical (Cl)',
            'Practical' => 'Practical (Pr)',
            'Medical' => 'Medical (Me)',
        ];

        return [
            ++$this->rowNumber,
            $hasil->tanggal_pengerjaan ? \Carbon\Carbon::parse($hasil->tanggal_pengerjaan)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') : 'N/A',
            $hasil->karirDataDiri->nim ?? 'N/A',
            $hasil->karirDataDiri->nama ?? 'N/A',
            $hasil->karirDataDiri->fakultas ?? 'N/A',
            $hasil->karirDataDiri->program_studi ?? 'N/A',
            $hasil->karirDataDiri->jenis_kelamin ?? 'N/A',
            $hasil->karirDataDiri->usia ?? 'N/A',
            $hasil->karirDataDiri->provinsi ?? 'N/A',
            $hasil->karirDataDiri->alamat ?? 'N/A',
            $hasil->karirDataDiri->email ?? 'N/A',
            $hasil->karirDataDiri->asal_sekolah ?? 'N/A',
            $hasil->karirDataDiri->status_tinggal ?? 'N/A',
            $hasil->karirDataDiri->prodi_sesuai_keinginan ?? 'N/A',
            $kategoriRMIB[$hasil->top_1_pekerjaan] ?? $hasil->top_1_pekerjaan ?? 'N/A',
            $kategoriRMIB[$hasil->top_2_pekerjaan] ?? $hasil->top_2_pekerjaan ?? 'N/A',
            $kategoriRMIB[$hasil->top_3_pekerjaan] ?? $hasil->top_3_pekerjaan ?? 'N/A',
            $hasil->interpretasi ?? 'N/A',
        ];
    }

    /**
     * Mengatur style untuk spreadsheet
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
