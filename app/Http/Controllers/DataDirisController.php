<?php //controller for data diri

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // ⚡ CACHING: Import Cache facade
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use App\Http\Requests\StoreDataDiriRequest;

class DataDirisController extends Controller
{
    /**
     * Menampilkan halaman form untuk mengisi data diri mahasiswa.
     */
    public function create()
    {
        // Mencari data diri berdasarkan NIM user yang sedang login
        $dataDiri = DataDiris::where('nim', Auth::user()->nim)->first();

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
            // SOLUSI: Ganti firstOrCreate dengan updateOrCreate
            // Ini akan mencari data diri berdasarkan NIM. Jika ada, akan di-update.
            // Jika tidak ada, akan dibuat baru.
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

            // Selalu buat entri riwayat keluhan baru setiap kali form ini disubmit
            RiwayatKeluhans::create([
                'nim' => $user->nim,
                'keluhan' => $validated['keluhan'],
                'lama_keluhan' => $validated['lama_keluhan'],
                'pernah_konsul' => $validated['pernah_konsul'],
                'pernah_tes' => $validated['pernah_tes'],
            ]);

            DB::commit();

            // ⚡ CACHING: Invalidate caches after updating data diri
            // Only invalidate admin caches (user-stats, fakultas-stats)
            // because data diri affects demographics but not test results
            Cache::forget('mh.admin.user_stats');
            Cache::forget('mh.admin.fakultas_stats');

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
