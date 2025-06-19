<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKeluhans;
use Illuminate\Http\Request;

class RiwayatKeluhansController extends Controller
{
    public function index()
    {
        $riwayatKeluhans = RiwayatKeluhans::with('dataDiri')->get();
        return view('riwayat_keluhans.index', compact('riwayatKeluhans'));
    }

    public function create()
    {
        return view('riwayat_keluhans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_diri_id' => 'required|exists:data_diris,id',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ]);

        RiwayatKeluhans::create($validated);
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil dibuat.');
    }

    public function show(RiwayatKeluhans $riwayatKeluhan)
    {
        return view('riwayat_keluhans.show', compact('riwayatKeluhan'));
    }

    public function edit(RiwayatKeluhans $riwayatKeluhan)
    {
        return view('riwayat_keluhans.edit', compact('riwayatKeluhan'));
    }

    public function update(Request $request, RiwayatKeluhans $riwayatKeluhan)
    {
        $validated = $request->validate([
            'data_diri_id' => 'required|exists:data_diris,id',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ]);

        $riwayatKeluhan->update($validated);
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil diperbarui.');
    }

    public function destroy(RiwayatKeluhans $riwayatKeluhan)
    {
        $riwayatKeluhan->delete();
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil dihapus.');
    }
}

