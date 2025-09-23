<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKeluhans;
use Illuminate\Http\Request;

class RiwayatKeluhansController extends Controller
{
    // Menampilkan halaman indeks yang berisi semua riwayat keluhan
    public function index()
    {
        // Mengambil semua data riwayat keluhan beserta relasi dataDiri
        $riwayatKeluhans = RiwayatKeluhans::with('dataDiri')->get();
        // Mengirim data ke view riwayat_keluhans.index
        return view('riwayat_keluhans.index', compact('riwayatKeluhans'));
    }

    // Menampilkan formulir untuk membuat riwayat keluhan baru
    public function create()
    {
        // Merender view formulir pembuatan data
        return view('riwayat_keluhans.create');
    }

    // Menyimpan data riwayat keluhan baru ke database
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'data_diri_id' => 'required|exists:data_diris,id', // Memastikan data_diri_id valid dan ada di tabel data_diris
            'keluhan' => 'required|string', // Validasi keluhan wajib diisi dan berupa string
            'lama_keluhan' => 'required|string', // Validasi lama keluhan wajib diisi dan berupa string
            'pernah_konsul' => 'required|in:Ya,Tidak', // Validasi harus Ya atau Tidak
            'pernah_tes' => 'required|in:Ya,Tidak', // Validasi harus Ya atau Tidak
        ]);

        // Membuat record baru di database
        RiwayatKeluhans::create($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil dibuat.');
    }

    // Menampilkan detail riwayat keluhan tertentu
    public function show(RiwayatKeluhans $riwayatKeluhan)
    {
        // Menampilkan view detail dengan data riwayat keluhan
        return view('riwayat_keluhans.show', compact('riwayatKeluhan'));
    }

    // Menampilkan form edit untuk riwayat keluhan
    public function edit(RiwayatKeluhans $riwayatKeluhan)
    {
        // Mengirim data riwayat keluhan ke view formulir edit
        return view('riwayat_keluhans.edit', compact('riwayatKeluhan'));
    }

    // Memperbarui data riwayat keluhan di database
    public function update(Request $request, RiwayatKeluhans $riwayatKeluhan)
    {
        // Validasi data yang diinput user
        $validated = $request->validate([
            'data_diri_id' => 'required|exists:data_diris,id', // Validasi ID data diri
            'keluhan' => 'required|string', // Memastikan keluhan terisi
            'lama_keluhan' => 'required|string', // Memastikan lama keluhan terisi
            'pernah_konsul' => 'required|in:Ya,Tidak', // Memastikan nilai valid
            'pernah_tes' => 'required|in:Ya,Tidak', // Memastikan nilai valid
        ]);

        // Melakukan update data ke database
        $riwayatKeluhan->update($validated);
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil diperbarui.');
    }

    // Menghapus riwayat keluhan dari database
    public function destroy(RiwayatKeluhans $riwayatKeluhan)
    {
        // Menghapus record dari database
        $riwayatKeluhan->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('riwayat_keluhans.index')->with('success', 'Riwayat keluhan berhasil dihapus.');
    }
}

