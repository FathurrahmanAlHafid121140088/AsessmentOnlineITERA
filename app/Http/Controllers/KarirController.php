<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Services\RmibScoringService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KarirController extends Controller
{
    // ========================
    // USER SIDE
    // ========================

    // 1. Form Data Diri
    public function showDataDiri()
    {
        return view('karir-datadiri');
    }

    // 2. Simpan Data Diri
    public function storeDataDiri(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nim'           => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string'
        ]);

        $dataDiri = KarirDataDiri::create($request->all());

        return redirect()->route('karir.form', ['id' => $dataDiri->id]);
    }

    // 3. Tampilkan Form RMIB
    public function showForm($id)
    {
        $dataDiri = KarirDataDiri::findOrFail($id);
        return view('karir-form', compact('dataDiri'));
    }

    // 4. Simpan Jawaban RMIB
    public function storeForm(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataDiri = KarirDataDiri::findOrFail($id);

            // Simpan hasil tes kosong
            $hasil = RmibHasilTes::create([
                'user_id'            => $dataDiri->id,
                'tanggal_pengerjaan' => Carbon::now(),
                'top_1_pekerjaan'    => '',
                'top_2_pekerjaan'    => '',
                'top_3_pekerjaan'    => '',
                'interpretasi'       => null,
                'nama'               => $dataDiri->nama,
                'nim'                => $dataDiri->nim,
                'program_studi'      => $dataDiri->program_studi
            ]);

            // Simpan jawaban user
            foreach ($request->input('jawaban', []) as $kelompok => $pekerjaanList) {
                foreach ($pekerjaanList as $pekerjaan => $peringkat) {
                    RmibJawabanPeserta::create([
                        'hasil_id'  => $hasil->id_hasil,
                        'kelompok'  => $kelompok,
                        'pekerjaan' => $pekerjaan,
                        'peringkat' => $peringkat
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('karir.interpretasi', ['id_hasil' => $hasil->id_hasil]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // 5. Halaman Interpretasi User
    public function interpretasi($id_hasil)
    {
        $hasil = RmibHasilTes::findOrFail($id_hasil);
        return view('karir-interpretasi', compact('hasil'));
    }

    // ========================
    // ADMIN SIDE
    // ========================

    // 1. Daftar semua peserta
    public function adminIndex()
    {
        $peserta = RmibHasilTes::orderBy('tanggal_pengerjaan', 'desc')->get();
        return view('admin-karir', compact('peserta'));
    }

    // 2. Detail hasil peserta + perhitungan RMIB
    public function adminDetail($id_hasil)
    {
        $hasil   = RmibHasilTes::findOrFail($id_hasil);
        $jawaban = RmibJawabanPeserta::where('hasil_id', $id_hasil)->get();

        // Hitung skor RMIB
        $scoringService = new RmibScoringService();
        $skor = $scoringService->hitungSkor($jawaban);

        // Ambil 3 kategori dominan (skor terendah)
        asort($skor);
        $top3 = array_slice(array_keys($skor), 0, 3);

        // Simpan ke tabel hasil tes
        $hasil->update([
            'top_1_pekerjaan' => $top3[0] ?? '',
            'top_2_pekerjaan' => $top3[1] ?? '',
            'top_3_pekerjaan' => $top3[2] ?? '',
            'interpretasi'    => 'Kategori dominan: ' . implode(', ', $top3)
        ]);

        return view('karir-detail-hasil', compact('hasil', 'jawaban', 'skor'));
    }
}
