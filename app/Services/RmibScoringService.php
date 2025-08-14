<?php

namespace App\Services;

class RmibScoringService
{
    protected $kategoriBarisAwal = [
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
        'E' => 4,
        'F' => 5,
        'G' => 6,
        'H' => 7,
        'I' => 8,
    ];

    protected $bidangMinat = [
        'OUT', 'ME', 'COMP', 'SCI', 'PERS', 'AESTH',
        'LIT', 'MUS', 'S.S', 'CLER', 'PRAC', 'MED'
    ];

    public function hitungSkor(array $peringkatUser): array
    {
        $skor = array_fill(0, 12, 0);

        foreach ($peringkatUser as $kategori => $nilaiArray) {
            if (!isset($this->kategoriBarisAwal[$kategori])) continue;

            $barisAwal = $this->kategoriBarisAwal[$kategori];

            for ($i = 0; $i < 12; $i++) {
                $barisIndex = ($barisAwal + $i) % 12;
                $skor[$barisIndex] += (int) $nilaiArray[$i];
            }
        }

        // Gabungkan skor dengan label bidang minat
        $hasil = [];
        foreach ($this->bidangMinat as $i => $label) {
            $hasil[$label] = $skor[$i];
        }

        // Urutkan untuk ambil top 3 minat dominan (skor terendah)
        asort($hasil);
        $top3 = array_slice($hasil, 0, 3, true);

        return [
            'skor' => $hasil,
            'top3' => $top3,
            'total' => array_sum($skor)
        ];
    }
}