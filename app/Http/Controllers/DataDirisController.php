<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Facades\DB;

class DataDirisController extends Controller
{
    public function create()
    {
        return view('isi-data-diri', [
            'title' => 'Form Data Diri'
        ]);
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'nim' => 'required|string',
        'nama' => 'required|string',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat' => 'required|string',
        'usia' => 'required|integer|min:1',
        'fakultas' => 'required|string',
        'program_studi' => 'required|string',
        'email' => 'required|email',
        'keluhan' => 'required|string',
        'lama_keluhan' => 'required|string',
        'pernah_konsul' => 'required|in:Ya,Tidak',
        'pernah_tes' => 'required|in:Ya,Tidak',
    ]);

    DB::beginTransaction();

    try {
        // Cek apakah data diri sudah ada
        $existing = DataDiris::where('nim', $validated['nim'])->first();

        if (!$existing) {
            // Simpan data diri baru jika belum ada
            DataDiris::create([
                'nim' => $validated['nim'],
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'usia' => $validated['usia'],
                'fakultas' => $validated['fakultas'],
                'program_studi' => $validated['program_studi'],
                'email' => $validated['email'],
            ]);
        }

        // Selalu simpan riwayat keluhan
        RiwayatKeluhans::create([
            'nim' => $validated['nim'],
            'keluhan' => $validated['keluhan'],
            'lama_keluhan' => $validated['lama_keluhan'],
            'pernah_konsul' => $validated['pernah_konsul'],
            'pernah_tes' => $validated['pernah_tes'],
        ]);

        DB::commit();

        return redirect()->route('mental-health.kuesioner')->with('success', 'Data berhasil disimpan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
    }
}
}
