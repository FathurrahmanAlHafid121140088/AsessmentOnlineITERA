<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Services\RmibScoringService;
use Carbon\Carbon;

class KarirController extends Controller
{
    public function showDataDiri()
    {
        return view('karir-datadiri');
    }

    public function storeDataDiri(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'usia' => 'required|numeric|min:1|max:150',
            'fakultas' => 'required|string',
            'program_studi' => 'required|string',
            'email' => 'required|email',
        ]);

        $dataDiri = KarirDataDiri::create($validated);

        // Simpan ID ke session untuk digunakan nanti
        session(['karir_data_diri_id' => $dataDiri->id]);

        return redirect()->route('karir.form', ['id' => $dataDiri->id]);
    }

    public function form($id)
    {
        $dataDiri = KarirDataDiri::findOrFail($id);
        
        // Load data pekerjaan berdasarkan gender
        $gender = $dataDiri->jenis_kelamin;
        $jsonFile = $gender == 'L' ? 'data/pekerjaan_pria.json' : 'data/pekerjaan_wanita.json';
        $jsonPath = public_path($jsonFile);
        
        $pekerjaan = [];
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            $pekerjaan = $jsonData;
        } else {
            // Fallback jika file tidak ada
            $pekerjaan = [
                'Kategori A' => ['Pekerjaan A1', 'Pekerjaan A2', 'Pekerjaan A3'],
                'Kategori B' => ['Pekerjaan B1', 'Pekerjaan B2', 'Pekerjaan B3'],
            ];
        }

        return view('karir-form', [
            'dataDiri' => $dataDiri,
            'gender' => $dataDiri->jenis_kelamin,
            'pekerjaan' => $pekerjaan,
        ]);
    }

public function storeJawaban(Request $request, $id)
{
    $dataDiri = KarirDataDiri::findOrFail($id);

    $peringkat = $request->input('peringkat');
    if (!$peringkat) {
        return redirect()->back()->withErrors(['peringkat' => 'Data peringkat tidak terkirim.']);
    }

    RmibJawabanPeserta::updateOrCreate(
        ['karir_data_diri_id' => $dataDiri->id],
        ['peringkat' => $peringkat]
    );

    // Hitung hasil RMIB
    $hasil = app(\App\Services\RmibScoringService::class)->hitung(json_decode($peringkat, true));

    // Simpan hasil ke tabel rmib_hasil_tes
    RmibHasilTes::updateOrCreate(
        ['user_id' => $dataDiri->id],
        [
            'tanggal_pengerjaan' => now(),
            'top_1_pekerjaan' => $hasil['top3'][0]['kategori'] ?? null,
            'top_2_pekerjaan' => $hasil['top3'][1]['kategori'] ?? null,
            'top_3_pekerjaan' => $hasil['top3'][2]['kategori'] ?? null,
            'interpretasi' => json_encode($hasil['skor']),
            'nama' => $dataDiri->nama,
            'nim' => $dataDiri->nim ?? '',
            'program_studi' => $dataDiri->program_studi ?? ''
        ]
    );

    return redirect()->route('karir.interpretasi', ['id' => $dataDiri->id])
        ->with('success', 'Jawaban berhasil disimpan.');
}

        public function adminDetail($id)
    {
        $peserta = KarirDataDiri::findOrFail($id);
        $jawaban = $peserta->rmibJawabanPeserta;
        $peringkat = json_decode($jawaban->peringkat, true);

        $hasil = app(RmibScoringService::class)->hitung($peringkat);

        // Simpan hasil jika belum ada
        RmibHasilTes::updateOrCreate(
            ['user_id' => $peserta->id],
            [
                'tanggal_pengerjaan' => now(),
                'top_1_pekerjaan' => array_key_first($hasil['top3']),
                'top_2_pekerjaan' => array_keys($hasil['top3'])[1],
                'top_3_pekerjaan' => array_keys($hasil['top3'])[2],
                'interpretasi' => json_encode($hasil['skor']),
                'nama' => $peserta->nama,
                'nim' => $peserta->nim ?? 'N/A',
                'program_studi' => $peserta->program_studi
            ]
        );

        return view('karir-detail-hasil', [
            'peserta' => $peserta,
            'peringkat' => $peringkat,
            'skor' => $hasil['skor'],
            'top3' => $hasil['top3'],
            'total' => $hasil['total'],
        ]);
    }

    // public function interpretasi($id)
    // {
    //     $hasilTes = RmibHasilTes::with(['jawaban', 'dataDiri'])->findOrFail($id);
        
    //     return view('karir-interpretasi', [
    //         'hasilTes' => $hasilTes,
    //         'dataDiri' => $hasilTes->dataDiri,
    //     ]);
    // }
    public function interpretasi($id)
    {
        $peserta = KarirDataDiri::findOrFail($id);
        $hasil = RmibHasilTes::where('user_id', $id)->first();
        $skor = $hasil ? json_decode($hasil->interpretasi, true) : [];

        return view('karir-interpretasi', [
            'dataDiri' => $peserta,
            'skor' => $skor,
            'top3' => array_slice($skor, 0, 3, true)
        ]);
    }
    

    private function generateInterpretasi($hasilTes)
    {
        // Contoh interpretasi sederhana berdasarkan top 3 pekerjaan
        $top1 = $hasilTes->top_1_pekerjaan;
        $top2 = $hasilTes->top_2_pekerjaan;
        $top3 = $hasilTes->top_3_pekerjaan;

        $interpretasi = "Berdasarkan hasil tes RMIB Anda:\n\n";
        $interpretasi .= "Pekerjaan yang paling Anda minati adalah: {$top1}\n";
        $interpretasi .= "Diikuti oleh: {$top2}\n";
        $interpretasi .= "Dan: {$top3}\n\n";
        $interpretasi .= "Hal ini menunjukkan bahwa Anda memiliki minat yang kuat terhadap bidang-bidang tersebut.";

        return $interpretasi;
    }
}