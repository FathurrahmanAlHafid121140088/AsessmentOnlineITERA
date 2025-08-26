<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKuesioner;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $kategoriFilter = $request->input('kategori'); // kategori dari dropdown
        $keyword = $request->input('search'); // input search text
        $limit = $request->input('limit', 10);

        $query = HasilKuesioner::with(['dataDiri', 'riwayatKeluhans']);

        // Filter berdasarkan kategori jika ada
        if ($kategoriFilter) {
            $query->where('kategori', $kategoriFilter);
        }

        // Filter pencarian text
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nim', 'like', "%$keyword%")
                    ->orWhere('total_skor', 'like', "%$keyword%")
                    ->orWhere('kategori', 'like', "%$keyword%")
                    ->orWhereHas('dataDiri', function ($q2) use ($keyword) {
                        $q2->where('nama', 'like', "%$keyword%")
                            ->orWhere('program_studi', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->orWhere('alamat', 'like', "%$keyword%")
                            ->orWhere('jenis_kelamin', 'like', "%$keyword%")
                            ->orWhere('fakultas', 'like', "%$keyword%")
                            ->orWhere('asal_sekolah', 'like', "%$keyword%")
                            ->orWhere('provinsi', 'like', "%$keyword%")
                            ->orWhere('status_tinggal', 'like', "%$keyword%");
                    })
                    ->orWhereHas('riwayatKeluhans', function ($q3) use ($keyword) {
                        $q3->where('keluhan', 'like', "%$keyword%")
                            ->orWhere('lama_keluhan', 'like', "%$keyword%")
                            ->orWhere('pernah_konsul', 'like', "%$keyword%")
                            ->orWhere('pernah_tes', 'like', "%$keyword%");
                    });
            });
        }

        $hasilKuesioners = $query->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->withQueryString();

        $kategoriOptions = [
            'Perlu Dukungan Intensif',
            'Perlu Dukungan',
            'Cukup Sehat',
            'Sehat',
            'Sangat Sehat'
        ];

        $message = $hasilKuesioners->isEmpty()
            ? 'Data tidak ditemukan.'
            : 'Data berhasil ditemukan!';

        return view('admin-home', compact('hasilKuesioners', 'kategoriOptions', 'kategoriFilter', 'keyword', 'message'));
    }
}
