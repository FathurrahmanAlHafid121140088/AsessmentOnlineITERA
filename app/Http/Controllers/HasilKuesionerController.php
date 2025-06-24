<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKuesioner;

class HasilKuesionerController extends Controller
{
    public function index()
    {
        // Ambil semua data hasil kuesioner dengan relasi ke data_diri
        $hasilKuesioners = HasilKuesioner::with('dataDiri')->get();

        return view('admin-home', [
            'title' => 'Admin',
            'hasilKuesioners' => $hasilKuesioners
        ]);
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
