<?php

namespace App\Services;

use App\Models\RmibJawabanPeserta;

class RmibScoringService
{
    protected $kategoriRMIB = ['OUT', 'ME', 'COMP', 'SCI', 'PERS', 'AESTH', 'LIT', 'MUS', 'S.S', 'CLER', 'PRAC', 'MED'];
    protected $kelompokList = ['Kelompok A', 'Kelompok B', 'Kelompok C', 'Kelompok D', 'Kelompok E', 'Kelompok F', 'Kelompok G', 'Kelompok H', 'Kelompok I'];

    public function hitung($hasilId)
    {
        $jawaban = RmibJawabanPeserta::where('hasil_id', $hasilId)->get();

        $skorKategori = array_fill_keys($this->kategoriRMIB, 0);

        foreach ($jawaban as $j) {
            $kelompokIndex = array_search($j->kelompok, $this->kelompokList);
            if ($kelompokIndex === false) continue;

            // Circular shift
            $shifted = [];
            for ($i = 0; $i < 12; $i++) {
                $shifted[] = $this->kategoriRMIB[($i + $kelompokIndex) % 12];
            }

            // Hitung row index dalam kelompok (0–11)
            $kelompokJobs = $jawaban->where('kelompok', $j->kelompok)->pluck('pekerjaan')->values()->toArray();
            $rowIndex = array_search($j->pekerjaan, $kelompokJobs);
            if ($rowIndex === false) continue;

            $kategori = $shifted[$rowIndex];
            $bobot = 13 - intval($j->peringkat); // Semakin kecil peringkat, semakin tinggi bobot
            $skorKategori[$kategori] += $bobot;
        }

        // Urutkan hasil
        $sorted = collect($skorKategori)->sortDesc();

        // Hitung peringkat dengan tie-break (rata-rata jika ada skor sama)
        $rankings = $this->generateRankingsWithTies($sorted);

        return [
            'skor' => $skorKategori,
            'sorted' => $sorted,
            'rankings' => $rankings,
        ];
    }

    protected function generateRankingsWithTies($sorted)
    {
        $rankings = [];
        $currentRank = 1;
        $tieGroup = [];
        $lastScore = null;
        $index = 0;

        foreach ($sorted as $kategori => $score) {
            $index++;

            if ($score !== $lastScore && count($tieGroup) > 0) {
                // Selesaikan tie group sebelumnya
                $avgRank = array_sum($tieGroup) / count($tieGroup);
                foreach ($pendingKategori as $kat) {
                    $rankings[$kat] = $avgRank;
                }

                $tieGroup = [];
                $pendingKategori = [];
            }

            $tieGroup[] = $index;
            $pendingKategori[] = $kategori;
            $lastScore = $score;
        }

        // Proses sisa (jika ada)
        if (count($tieGroup) > 0) {
            $avgRank = array_sum($tieGroup) / count($tieGroup);
            foreach ($pendingKategori as $kat) {
                $rankings[$kat] = $avgRank;
            }
        }

        return $rankings;
    }
}
