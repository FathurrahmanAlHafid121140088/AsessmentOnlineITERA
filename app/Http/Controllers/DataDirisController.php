<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;

class DataDirisController extends Controller
{
    /**
     * Menampilkan halaman form untuk mengisi data diri mahasiswa.
     */
    public function create()
    {
        $dataDiri = DataDiris::find(Auth::user()->nim);

        return view('isi-data-diri', [
            'title' => 'Form Data Diri',
            'dataDiri' => $dataDiri
        ]);
    }

    /**
     * Membuat data diri jika belum ada, dan selalu menyimpan riwayat keluhan baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'provinsi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'usia' => 'required|integer|min:1',
            'fakultas' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'status_tinggal' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string|max:255',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ]);

        DB::beginTransaction();

        try {
            $dataDiri = DataDiris::firstOrCreate(
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

            RiwayatKeluhans::create([
                'nim' => $user->nim,
                'keluhan' => $validated['keluhan'],
                'lama_keluhan' => $validated['lama_keluhan'],
                'pernah_konsul' => $validated['pernah_konsul'],
                'pernah_tes' => $validated['pernah_tes'],
            ]);

            DB::commit();

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

