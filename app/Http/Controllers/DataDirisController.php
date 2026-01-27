<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use App\Models\HasilKuesioner; // ✅ Tambahkan Model HasilKuesioner
use App\Http\Requests\StoreDataDiriRequest;

class DataDirisController extends Controller
{
    /**
     * Menampilkan halaman form untuk mengisi data diri mahasiswa.
     * Cek dulu apakah ada tes on_progress.
     */
    public function create()
    {
        $user = Auth::user();

        // 1. CEK APAKAH ADA KUESIONER 'ON_PROGRESS'
        // Kita cek tabel hasil_kuesioners berdasarkan NIM user
        $existingTest = HasilKuesioner::where('nim', $user->nim)
            ->where('status', 'on_progress')
            ->first();

        // 2. JIKA ADA, LEMPAR LANGSUNG KE HALAMAN SOAL (RESUME)
        if ($existingTest) {
            $nextStep = $existingTest->posisi_soal_terakhir + 1;

            // Redirect ke route soal dengan membawa pesan alert
            return redirect()->route('quiz.show', $nextStep)
                ->with('resume_alert', 'Anda memiliki tes yang belum diselesaikan. Harap selesaikan terlebih dahulu sebelum mengisi data baru.');
        }

        // 3. JIKA TIDAK ADA, LANJUT TAMPILKAN FORM DATA DIRI (Logika Asli)
        $dataDiri = DataDiris::where('nim', $user->nim)->first();

        return view('isi-data-diri', [
            'title' => 'Form Data Diri',
            'dataDiri' => $dataDiri
        ]);
    }

    /**
     * Memperbarui atau membuat data diri, dan selalu menyimpan riwayat keluhan baru.
     */
    public function store(StoreDataDiriRequest $request)
    {
        $user = Auth::user();

        // Data sudah tervalidasi otomatis oleh FormRequest
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Update atau Create Data Diri
            $dataDiri = DataDiris::updateOrCreate(
                ['nim' => $user->nim],
                [
                    'nama' => $validated['nama'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'provinsi' => $validated['provinsi'],
                    'alamat' => $validated['alamat'],
                    'usia' => $validated['usia'],
                    'fakultas' => $validated['fakultas'],
                    'program_studi' => $validated['program_studi'],
                    'asal_sekolah' => $validated['asal_sekolah'],
                    'status_tinggal' => $validated['status_tinggal'],
                    'email' => $validated['email'],
                ]
            );

            // Selalu buat entri riwayat keluhan baru
            RiwayatKeluhans::create([
                'nim' => $user->nim,
                'keluhan' => $validated['keluhan'],
                'lama_keluhan' => $validated['lama_keluhan'],
                'pernah_konsul' => $validated['pernah_konsul'],
                'pernah_tes' => $validated['pernah_tes'],
            ]);

            DB::commit();

            // ⚡ CACHING: Bersihkan cache admin
            Cache::forget('mh.admin.user_stats');
            Cache::forget('mh.admin.fakultas_stats');

            // Simpan info ke session untuk digunakan di quiz nanti
            session([
                'nim' => $user->nim,
                'nama' => $dataDiri->nama,
                'program_studi' => $dataDiri->program_studi
            ]);

            return redirect()
                ->route('mental-health.kuesioner')
                ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }
}