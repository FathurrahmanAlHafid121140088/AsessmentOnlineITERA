<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use Illuminate\Support\Collection;

class HasilKuesionerController extends Controller
{
public function index(Request $request)
{
    $query = $request->input('search');

    $hasilKuesioners = HasilKuesioner::with(['dataDiri', 'riwayatKeluhans'])
        ->when($query, function ($q) use ($query) {
            $q->where('nim', 'like', "%{$query}%")
              ->orWhereHas('dataDiri', function ($q2) use ($query) {
                  $q2->where('nama', 'like', "%{$query}%")
                      ->orWhere('program_studi', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('alamat', 'like', "%{$query}%")
                      ->orWhere('jenis_kelamin', 'like', "%{$query}%")
                      ->orWhere('fakultas', 'like', "%{$query}%");
              });
        })
        ->get()
        ->groupBy('nim') // Agar 1 data per NIM
        ->map(fn($items) => $items->first()) // Ambil hanya 1 hasil per NIM
        ->values();

    $totalUsers = $hasilKuesioners->count(); // Hitung total unik NIM

    return view('admin-home', [
        'title' => 'Admin', // ✅ tambahkan ini
        'hasilKuesioners' => $hasilKuesioners,
        'totalUsers' => $totalUsers
    ]);}
    public function storeKuesioner(Request $request)
{
    $validated = $request->validate([
        'total_skor' => 'required|integer|min:0',
        'kategori' => 'required|string|max:255',
    ]);

    $nim = session('nim');

    if (!$nim) {
        return redirect()->route('mental-health.data-diri')->withErrors(['error' => 'NIM tidak ditemukan. Silakan isi data diri terlebih dahulu.']);
    }

    // Cek apakah data sudah pernah disimpan
    $existing = HasilKuesioner::where('nim', $nim)->first();
    if (!$existing) {
        HasilKuesioner::create([
            'nim' => $nim,
            'total_skor' => $validated['total_skor'],
            'kategori' => $validated['kategori'],
        ]);
    }

    return redirect()->route('admin.home')->with('success', 'Hasil kuesioner berhasil disimpan.');
}
    public function destroy($id)
    {
        $hasil = HasilKuesioner::find($id);

        if (!$hasil) {
            return redirect()->route('admin.home')->with('error', 'Data tidak ditemukan.');
        }

        $hasil->delete();

        return redirect()->route('admin.home')->with('success', 'Data berhasil dihapus.');
    }
}
