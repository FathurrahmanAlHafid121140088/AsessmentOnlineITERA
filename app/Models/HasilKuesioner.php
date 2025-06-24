<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HasilKuesioner extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuesioners';

    protected $fillable = [
        'nim', 'total_skor', 'kategori', 'created_at', 'updated_at'
    ];

    // Relasi ke DataDiris
    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'nim', 'nim');
    }
    public function index(Request $request)
{
    $query = $request->input('search');

    $hasilKuesioners = HasilKuesioner::with('dataDiri')
        ->when($query, function($q) use ($query) {
            $q->where('nim', 'like', "%{$query}%")
              ->orWhereHas('dataDiri', function($q2) use ($query) {
                  $q2->where('nama', 'like', "%{$query}%")
                     ->orWhere('program_studi', 'like', "%{$query}%")
                     ->orWhere('email', 'like', "%{$query}%")
                     ->orWhere('alamat', 'like', "%{$query}%")
                     ->orWhere('jenis_kelamin', 'like', "%{$query}%")
                     ->orWhere('fakultas', 'like', "%{$query}%");
              });
        })
        ->get();

    return view('admin-home', [
        'title' => 'Admin',
        'hasilKuesioners' => $hasilKuesioners
    ]);
}
}
