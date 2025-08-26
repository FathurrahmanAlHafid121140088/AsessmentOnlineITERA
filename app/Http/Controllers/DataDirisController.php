<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;

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
            'provinsi' => 'required|string',
            'alamat' => 'required|string',
            'usia' => 'required|integer|min:1',
            'fakultas' => 'required|string',
            'program_studi' => 'required|string',
            'asal_sekolah' => 'required|string',
            'status_tinggal' => 'required|string',
            'email' => 'required|email',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ]);

        DB::beginTransaction();

        try {
            $dataDiri = DataDiris::firstOrCreate(
                ['nim' => $validated['nim']],
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
                'nim' => $dataDiri->nim,
                'keluhan' => $validated['keluhan'],
                'lama_keluhan' => $validated['lama_keluhan'],
                'pernah_konsul' => $validated['pernah_konsul'],
                'pernah_tes' => $validated['pernah_tes'],
            ]);

            // simpan nim ke session
            session([
                'nim' => $dataDiri->nim,
                'nama' => $dataDiri->nama,
                'program_studi' => $dataDiri->program_studi
            ]);
            DB::commit();

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

    public function search(Request $request)
    {
        $keyword = $request->input('query');

        $results = DataDiris::with(['riwayatKeluhan', 'hasilKuesioner'])
            ->search($keyword)
            ->get();

        return response()->json($results);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nim', 'like', "%$keyword%")
                ->orWhere('nama', 'like', "%$keyword%")
                ->orWhere('jenis_kelamin', 'like', "%$keyword%")
                ->orWhere('provinsi', 'like', "%$keyword%")
                ->orWhere('alamat', 'like', "%$keyword%")
                ->orWhere('usia', 'like', "%$keyword%")
                ->orWhere('fakultas', 'like', "%$keyword%")
                ->orWhere('program_studi', 'like', "%$keyword%")
                ->orWhere('asal_sekolah', 'like', "%$keyword%")
                ->orWhere('status_tinggal', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
        })
            ->orWhereHas('riwayatKeluhans', function ($q) use ($keyword) {
                $q->where('keluhan', 'like', "%$keyword%")
                    ->orWhere('lama_keluhan', 'like', "%$keyword%")
                    ->orWhere('pernah_konsul', 'like', "%$keyword%")
                    ->orWhere('pernah_tes', 'like', "%$keyword%");
            })
            ->orWhereHas('hasilKuesioners', function ($q) use ($keyword) {
                $q->where('nim', 'like', "%$keyword%")
                    ->orWhere('total_skor', 'like', "%$keyword%")
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->orWhere('created_at', 'like', "%$keyword%");
            });
    }


}
