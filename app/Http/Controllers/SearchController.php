<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKuesioner;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('search');

        $hasilKuesioners = HasilKuesioner::with('dataDiri')
            ->when($query, function($q) use ($query) {
                $q->where('nim', 'like', "%{$query}%")
                  ->orWhereHas('dataDiri', function($q2) use ($query) {
                      $q2->where('nama', 'like', "%{$query}%")
                         ->orWhere('program_studi', 'like', "%{$query}%")
                         ->orWhere('fakultas', 'like', "%{$query}%")
                         ->orWhere('email', 'like', "%{$query}%")
                         ->orWhere('alamat', 'like', "%{$query}%");
                  });
            })
            ->get();

        return view('admin-home', [
            'title' => 'Admin',
            'hasilKuesioners' => $hasilKuesioners
        ]);
    }
}
