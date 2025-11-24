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
            'Prodi Sesuai Keinginan',
            'Jenis Kelamin',
            'Usia',
            'Provinsi',
            'Alamat',
            'Email',
            'Asal Sekolah',
            'Status Tinggal',
            'Top 1' ,
            'Top 2 ',
            'Top 3 ',
        ];
    }

    /**
     * Memetakan data dari setiap baris ke format array untuk Excel
     */
    public function map($hasil): array
    {
        return [
            ++$this->rowNumber,
            $hasil->tanggal_pengerjaan ? \Carbon\Carbon::parse($hasil->tanggal_pengerjaan)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') : 'N/A',
            $hasil->karirDataDiri->nim ?? 'N/A',
            $hasil->karirDataDiri->nama ?? 'N/A',
            $hasil->karirDataDiri->fakultas ?? 'N/A',
            $hasil->karirDataDiri->program_studi ?? 'N/A',
            $hasil->karirDataDiri->prodi_sesuai_keinginan ?? 'N/A',
            $hasil->karirDataDiri->jenis_kelamin ?? 'N/A',
            $hasil->karirDataDiri->usia ?? 'N/A',
            $hasil->karirDataDiri->provinsi ?? 'N/A',
            $hasil->karirDataDiri->alamat ?? 'N/A',
            $hasil->karirDataDiri->email ?? 'N/A',
            $hasil->karirDataDiri->asal_sekolah ?? 'N/A',
            $hasil->karirDataDiri->status_tinggal ?? 'N/A',
            $hasil->top_1_pekerjaan ?? 'N/A',
            $hasil->top_2_pekerjaan ?? 'N/A',
            $hasil->top_3_pekerjaan ?? 'N/A',
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
