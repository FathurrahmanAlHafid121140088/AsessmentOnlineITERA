<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\Users;

class KarirController extends Controller
{
    public function showKarirForm()
{
    $kategoriRMIB = [
        "Kelompok A" => [
            "Petani", "Insinyur Sipil", "Akuntan", "Ilmuwan", "Manager Penjualan", "Seniman",
            "Wartawan", "Pianis Konser", "Guru SD", "Manager Bank", "Tukang Kayu", "Dokter"
        ],
        "Kelompok B" => [
            "Ahli Pembuat Alat", "Ahli Statistik", "Insinyur Kimia Industri", "Penyiar Radio",
            "Artis Profesional", "Pengarang", "Dirigen Orkestra", "Psikolog Pendidikan",
            "Sekretaris Perusahaan", "Ahli Bangunan", "Ahli Bedah", "Ahli Kehutanan"
        ],
        "Kelompok C" => [
            "Auditor", "Ahli Meteorologi", "Salesman", "Arsitek", "Penulis Drama", "Komponis",
            "Kepala Sekolah", "Pegawai Kecamatan", "Ahli Meubel/ Furniture", "Dokter Hewan",
            "Juru Ukur Tanah", "Tukang Bubut/ Lemer"
        ],
        "Kelompok D" => [
            "Ahli Biologi", "Agen Biro Periklanan", "Dekorator Interior", "Ahli Sejarah",
            "Kritikus Musik", "Pekerja Sosial", "Pegawai Asuransi", "Tukang Cat", "Apoteker",
            "Penjelajah", "Tukang Listrik", "Penilai Pajak Pendapatan"
        ],
        "Kelompok E" => [
            "Petugas Wawancara", "Perancang Perhiasan", "Ahli Perpustakaan", "Guru Musik",
            "Pembina Rohani", "Petugas Arsip", "Tukang Batu", "Dokter Gigi", "Prospektor",
            "Montir", "Guru Ilmu Pasti", "Ahli Pertanian"
        ],
        "Kelompok F" => [
            "Fotografer", "Penulis Majalah", "Pemain Orgen Tunggal", "Organisator Kepramukaan",
            "Petugas Pengirim Barang", "Operator Mesin Perkayuan", "Ahli Kacamata",
            "Ahli Sortir Kulit", "Instalator", "Asisten Kasir Bank", "Ahli Botani", "Pedagang Keliling"
        ],
        "Kelompok G" => [
            "Kritikus Buku", "Ahli Pustaka Musik", "Pengurus Karang Taruna", "Pegawai Kantor",
            "Tukang Plester Tembok", "Ahli Rontgent", "Nelayan", "Pembuat Arloji", "Kasir",
            "Ahli Astronomi", "Juru Lelang", "Penata Panggung"
        ],
        "Kelompok H" => [
            "Pemain Musik Band", "Ahli Penyuluh Jabatan", "Pegawai Kantor Pos", "Tukang Ledeng/ Pipa Air",
            "Ahli Fisioterapi", "Sopir Angkutan Umum", "Montir Radio", "Juru Bayar", "Ahli Geologi",
            "Petugas Hubungan Masyarakat", "Penata Etalase", "Penulis Sandiwara Radio"
        ],
        "Kelompok I" => [
            "Petugas Kesejahteraan Sosial", "Petugas Ekspedisi Surat", "Tukang Sepatu",
            "Paramedik/Mantri Kesehatan", "Petani Tanaman Hias", "Tukang Las", "Petugas Pajak",
            "Asisten Laboratorium", "Salesman Asuransi", "Perancang Motif Tekstil", "Penyair",
            "Pramuniaga Toko Musik"
        ],
    ];

    $pekerjaan = collect($kategoriRMIB)->flatten()->unique()->sort()->values()->toArray();

    return view('karir-form', compact('pekerjaan'));
}

public function simpanDataDiri(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'jenis_kelamin' => 'required|in:L,P',
    ]);

    session(['gender' => $request->jenis_kelamin]);
    return redirect()->route('karir.form');
}

public function form()
{
    return view('karir-form'); // file resources/views/karir-form.blade.php
}

public function simpanHasil(Request $request)
{
    $request->validate([
        'peringkat' => 'required',
        'top1' => 'required|string',
        'top2' => 'required|string',
        'top3' => 'required|string',
    ]);

    $user = Users::find(1); // ID dummy sementara

    $peringkatData = json_decode($request->peringkat, true);

    $hasil = RmibHasilTes::create([
        'user_id' => $user->id,
        'tanggal_pengerjaan' => now(),
        'top_1_pekerjaan' => $request->top1,
        'top_2_pekerjaan' => $request->top2,
        'top_3_pekerjaan' => $request->top3,
        'nama' => $user->name,
        'nim' => $user->nim ?? null,
        'program_studi' => $user->program_studi ?? null,
    ]);

    // Simpan semua jawaban pekerjaan ke rmib_jawaban_peserta
    foreach ($peringkatData as $kelompok => $pekerjaanList) {
        foreach ($pekerjaanList as $pekerjaan => $peringkat) {
            RmibJawabanPeserta::create([
                'hasil_id' => $hasil->id_hasil,
                'kelompok' => $kelompok,
                'pekerjaan' => $pekerjaan,
                'peringkat' => $peringkat,
            ]);
        }
    }
    session(['gender' => $request->jenis_kelamin]); // 'L' atau 'P'

    return redirect()->route('kariri.interpretasi');
}

public function simpanJawaban(Request $request)
{
    // Validasi jika perlu
    $request->validate([
        'top_1' => 'required|string',
        'top_2' => 'required|string',
        'top_3' => 'required|string',
        'jawaban' => 'required|array',
    ]);

    // Proses simpan ke database atau session...
    // contoh:
    // RmibJawabanPeserta::create([...]);

    return redirect()->route('karir.hasil'); // atau ke mana pun setelah submit
}


}

