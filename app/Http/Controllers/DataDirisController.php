<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;

class DataDirisController extends Controller
{
    /**
     * Menampilkan halaman form untuk mengisi data diri mahasiswa.
     *
     * Fungsi ini hanya bertugas untuk mengembalikan view 'isi-data-diri'
     * beserta judul halaman yang akan ditampilkan di tab browser.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('isi-data-diri', [
            'title' => 'Form Data Diri'
        ]);
    }

    /**
     * Menyimpan data diri dan riwayat keluhan yang di-submit dari form.
     *
     * Fungsi ini melakukan beberapa hal:
     * 1. Validasi semua input yang masuk.
     * 2. Menggunakan Transaction untuk memastikan kedua data (data diri & riwayat) berhasil disimpan.
     * 3. Mencari atau membuat data diri baru berdasarkan NIM untuk menghindari duplikasi.
     * 4. Membuat entri baru untuk riwayat keluhan.
     * 5. Menyimpan informasi penting ke session untuk digunakan di halaman selanjutnya.
     * 6. Mengarahkan pengguna ke halaman kuesioner jika berhasil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form. Jika gagal, Laravel akan otomatis redirect kembali
        // dengan pesan error.
        $validated = $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'provinsi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'usia' => 'required|integer|min:1',
            'fakultas' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'status_tinggal' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string|max:255',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ]);

        // 2. Memulai Transaction. Ini penting untuk menjaga integritas data.
        // Jika salah satu proses (misal: simpan riwayat) gagal, maka proses simpan data diri
        // juga akan dibatalkan.
        DB::beginTransaction();

        try {
            // 3. Menggunakan firstOrCreate untuk efisiensi.
            // - Jika mahasiswa dengan NIM ini sudah ada, data dirinya akan diambil.
            // - Jika belum ada, data diri baru akan dibuat dengan data yang disediakan.
            $dataDiri = DataDiris::firstOrCreate(
                ['nim' => $validated['nim']], // Kunci untuk mencari
                [ // Data yang akan diisi jika data baru dibuat
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

            // 4. Membuat entri baru di tabel RiwayatKeluhans yang berelasi dengan data diri.
            RiwayatKeluhans::create([
                'nim' => $dataDiri->nim,
                'keluhan' => $validated['keluhan'],
                'lama_keluhan' => $validated['lama_keluhan'],
                'pernah_konsul' => $validated['pernah_konsul'],
                'pernah_tes' => $validated['pernah_tes'],
            ]);

            // 5. Menyimpan data penting ke dalam session untuk personalisasi di halaman kuesioner.
            session([
                'nim' => $dataDiri->nim,
                'nama' => $dataDiri->nama,
                'program_studi' => $dataDiri->program_studi
            ]);

            // 6. Jika semua proses di atas berhasil, konfirmasi perubahan ke database.
            DB::commit();

            // 7. Arahkan ke halaman kuesioner dengan pesan sukses.
            return redirect()
                ->route('mental-health.kuesioner')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // 8. Jika terjadi error di dalam blok 'try', batalkan semua query yang sudah dijalankan.
            DB::rollBack();

            // 9. Kembalikan pengguna ke halaman form dengan pesan error dan data input sebelumnya.
            return back()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Menangani request pencarian data mahasiswa secara asynchronous (AJAX).
     *
     * Fungsi ini memanggil 'scopeSearch' yang sudah dioptimalkan di dalam Model DataDiris.
     * Menggunakan 'with()' untuk Eager Loading, mencegah masalah N+1 query
     * saat mengambil data relasi (riwayatKeluhans, hasilKuesioners).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Ambil kata kunci pencarian dari request.
        $keyword = $request->input('query');

        // Lakukan query menggunakan scope 'search' dari model DataDiris.
        $results = DataDiris::with(['riwayatKeluhans', 'hasilKuesioners']) // Eager load relasi
            ->search($keyword) // Memanggil scopeSearch($keyword) yang sudah dioptimalkan
            ->get();

        // Kembalikan hasil dalam format JSON.
        return response()->json($results);
    }
}
