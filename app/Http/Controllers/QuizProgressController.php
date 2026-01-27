<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;
use App\Models\MentalHealthJawabanDetail;
use App\Models\DataDiris;

class QuizProgressController extends Controller
{
    private $questions = [
        1 => 'Seberapa bahagia, puas, atau senangkah Anda dengan kehidupan pribadi Anda selama sebulan terakhir?',
        2 => 'Seberapa sering Anda merasa kesepian selama sebulan terakhir?',
        3 => 'Seberapa sering Anda merasa gugup atau gelisah ketika dihadapkan pada situasi yang menyenangkan atau tak terduga selama sebulan terakhir?',
        4 => 'Selama sebulan terakhir, seberapa sering Anda merasa bahwa masa depan terlihat penuh harapan dan menjanjikan?',
        5 => 'Berapa banyak waktu, selama sebulan terakhir, kehidupan sehari-hari Anda penuh dengan hal-hal yang menarik bagi Anda?',
        6 => 'Seberapa sering, selama sebulan terakhir, Anda merasa rileks dan bebas dari ketegangan?',
        7 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menikmati hal-hal yang Anda lakukan?',
        8 => 'Selama sebulan terakhir, pernahkah Anda merasa kehilangan akal sehat, atau kehilangan kendali atas cara Anda bertindak, berbicara, berpikir, merasakan, atau ingatan Anda?',
        9 => 'Apakah Anda merasa tertekan selama sebulan terakhir?',
        10 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk merasa dicintai dan diinginkan?',
        11 => 'Seberapa sering, selama sebulan terakhir, Anda menjadi orang yang sangat gugup?',
        12 => 'Ketika Anda bangun di pagi hari, dalam sebulan terakhir ini, kira-kira seberapa sering Anda berharap untuk mendapatkan hari yang menarik?',
        13 => 'Seberapa sering Anda merasa tegang atau sangat gelisah?',
        14 => 'Selama sebulan terakhir, apakah Anda memegang kendali penuh atas perilaku, pikiran, emosi, atau perasaan Anda?',
        15 => 'Selama sebulan terakhir, seberapa sering tangan Anda bergetar ketika mencoba melakukan sesuatu?',
        16 => 'Selama sebulan terakhir, seberapa sering Anda merasa tidak memiliki sesuatu yang dinantikan?',
        17 => 'Seberapa sering, selama sebulan terakhir, Anda merasa tenang dan damai?',
        18 => 'Seberapa sering, selama sebulan terakhir, Anda merasa stabil secara emosional?',
        19 => 'Seberapa sering, selama sebulan terakhir, Anda merasa murung?',
        20 => 'Seberapa sering Anda merasa ingin menangis, selama sebulan terakhir?',
        21 => 'Selama sebulan terakhir, seberapa sering Anda merasa bahwa orang lain akan lebih baik jika Anda mati?',
        22 => 'Berapa banyak waktu, selama sebulan terakhir, Anda dapat bersantai tanpa kesulitan?',
        23 => 'Seberapa sering, selama sebulan terakhir, Anda merasa bahwa hubungan cinta Anda, mencintai dan dicintai, terasa utuh dan lengkap?',
        24 => 'Seberapa sering, selama sebulan terakhir, Anda merasa bahwa tidak ada yang berjalan sesuai dengan yang Anda inginkan?',
        25 => 'Seberapa sering Anda merasa terganggu oleh rasa gugup, atau "kegelisahan" Anda, selama sebulan terakhir?',
        26 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk menjalani petualangan yang luar biasa bagi Anda?',
        27 => 'Seberapa sering, selama sebulan terakhir, Anda merasa sangat terpuruk sehingga tidak ada yang dapat menghibur Anda?',
        28 => 'Selama sebulan terakhir, apakah Anda berpikir untuk bunuh diri?',
        29 => 'Selama sebulan terakhir, berapa kali Anda merasa gelisah, resah, atau tidak sabar?',
        30 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk murung atau merenung tentang berbagai hal?',
        31 => 'Seberapa sering, selama sebulan terakhir, Anda merasa ceria dan gembira?',
        32 => 'Selama sebulan terakhir, seberapa sering Anda merasa gelisah, kesal, atau bingung?',
        33 => 'Selama sebulan terakhir, apakah Anda pernah merasa cemas atau khawatir?',
        34 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menjadi orang yang bahagia?',
        35 => 'Seberapa sering selama sebulan terakhir Anda merasa perlu menenangkan diri?',
        36 => 'Selama sebulan terakhir, seberapa sering Anda merasa sedih atau sangat terpuruk?',
        37 => 'Seberapa sering, selama sebulan terakhir, Anda bangun tidur dengan perasaan segar dan beristirahat?',
        38 => 'Selama sebulan terakhir, apakah Anda pernah mengalami atau merasa berada di bawah tekanan, stres, atau tekanan?'
    ];

    public function start()
    {
        $user = Auth::user();
        $nim = $user->nim; // Gunakan NIM asli dari Auth

        // 1. Cek Resume
        $existingTest = HasilKuesioner::where('nim', $nim)
            ->where('status', 'on_progress')
            ->first();

        if ($existingTest) {
            $nextStep = $existingTest->posisi_soal_terakhir + 1;

            // --- TAMBAHKAN ->with() DI BAWAH INI ---
            return redirect()->route('quiz.show', $nextStep)
                ->with('resume_alert', 'Sistem mendeteksi Anda belum menyelesaikan tes sebelumnya. Silakan lanjutkan pengerjaan.');
        }

        // 2. Buat Baru
        HasilKuesioner::create([
            'nim' => $nim,
            'total_skor' => 0,
            'kategori' => null,
            'status' => 'on_progress',
            'posisi_soal_terakhir' => 0,
            'draft_jawaban' => json_encode([]),
        ]);

        return redirect()->route('quiz.show', 1);
    }

    public function show($step)
    {
        $step = (int) $step;
        $user = Auth::user();
        $nim = $user->nim;

        // 1. Ambil Data Tes
        $test = HasilKuesioner::where('nim', $nim)
            ->where('status', 'on_progress')
            ->first();

        // Jika tidak ada data, kembalikan ke halaman depan
        if (!$test) {
            return redirect()->route('mental-health.kuesioner');
        }

        // 2. Validasi Langkah (Agar tidak loncat)
        $allowedStep = $test->posisi_soal_terakhir + 1;

        // Jika user memaksa akses langkah lebih jauh, kembalikan ke langkah yang seharusnya
        if ($step > $allowedStep) {
            return redirect()->route('quiz.show', $allowedStep);
        }

        // 3. Ambil Data Diri (Handle kemungkinan NIM pakai titik di tabel DataDiri)
        // Kita coba cari pakai NIM Auth, kalau gagal coba bersihkan titiknya
        $dataDiri = DataDiris::where('nim', $nim)->first();

        if (!$dataDiri) {
            // Coba cari alternatif (misal di data diri pakai titik: 121.140...)
            // Kita coba format manual atau ambil apa adanya
            $nimWithDots = substr($nim, 0, 3) . '.' . substr($nim, 3, 3) . '.' . substr($nim, 6);
            $dataDiri = DataDiris::where('nim', $nimWithDots)->first();
        }

        $namaMhs = $dataDiri ? $dataDiri->nama : $user->name;
        $prodiMhs = $dataDiri ? $dataDiri->program_studi : '-';

        $questionText = $this->questions[$step] ?? 'Pertanyaan tidak ditemukan';

        return view('step', [
            'step' => $step,
            'total_steps' => 38,
            'question' => $questionText,
            'title' => 'Soal No ' . $step,
            'nama' => $namaMhs,
            'prodi' => $prodiMhs
        ]);
    }

    public function storeAnswer(Request $request, $step)
    {
        $request->validate(['answer' => 'required|integer|min:1|max:6']);

        $step = (int) $step;
        $user = Auth::user();

        $test = HasilKuesioner::where('nim', $user->nim)
            ->where('status', 'on_progress')
            ->first();

        if (!$test)
            return redirect()->route('quiz.start');

        $currentAnswers = json_decode($test->draft_jawaban, true) ?? [];
        $currentAnswers[$step] = (int) $request->answer;

        $test->update([
            'draft_jawaban' => json_encode($currentAnswers),
            'posisi_soal_terakhir' => $step
        ]);

        if ($step >= 38) {
            return $this->finalizeTest($test, $currentAnswers);
        }

        return redirect()->route('quiz.show', $step + 1);
    }

    private function finalizeTest($test, $answers)
    {
        DB::beginTransaction();
        try {
            if (count($answers) < 38) {
                return redirect()->route('quiz.show', count($answers) + 1);
            }

            $totalSkor = array_sum($answers);
            $kategori = match (true) {
                $totalSkor >= 190 && $totalSkor <= 226 => 'Sangat Sehat',
                $totalSkor >= 152 && $totalSkor <= 189 => 'Sehat',
                $totalSkor >= 114 && $totalSkor <= 151 => 'Cukup Sehat',
                $totalSkor >= 76 && $totalSkor <= 113 => 'Perlu Dukungan',
                $totalSkor >= 38 && $totalSkor <= 75 => 'Perlu Dukungan Intensif',
                default => 'Tidak Terdefinisi',
            };

            $details = [];
            $now = now();
            foreach ($answers as $noSoal => $skor) {
                $details[] = [
                    'hasil_kuesioner_id' => $test->id,
                    'nomor_soal' => $noSoal,
                    'skor' => $skor,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            MentalHealthJawabanDetail::insert($details);

            $test->update([
                'total_skor' => $totalSkor,
                'kategori' => $kategori,
                'status' => 'selesai',
                'draft_jawaban' => null
            ]);

            DB::commit();
            return redirect()->route('mental-health.hasil')->with('success', 'Tes Selesai!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('quiz.show', 38)->with('error', $e->getMessage());
        }
    }
}