<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RmibHasilTes;
use App\Services\RmibScoringService;
use Illuminate\Support\Facades\DB;

class RecalculateRmibResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmib:recalculate
                            {--force : Skip confirmation prompt}
                            {--id= : Only recalculate specific hasil_tes ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate top 3 categories for all RMIB test results based on matrix calculation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 RMIB Results Recalculation Tool');
        $this->info('=====================================');
        $this->newLine();

        // Get filter
        $specificId = $this->option('id');

        // Query hasil tes
        $query = RmibHasilTes::with('karirDataDiri');

        if ($specificId) {
            $query->where('id', $specificId);
        }

        $hasilTesList = $query->get();

        if ($hasilTesList->isEmpty()) {
            $this->error('❌ No test results found!');
            return 1;
        }

        $totalRecords = $hasilTesList->count();
        $this->info("📊 Found {$totalRecords} test result(s) to process.");
        $this->newLine();

        // Konfirmasi
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will OVERWRITE top_1_pekerjaan, top_2_pekerjaan, and top_3_pekerjaan columns. Continue?', true)) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->newLine();
        $this->info('🚀 Starting recalculation...');
        $this->newLine();

        // Progress bar
        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->start();

        $scoringService = new RmibScoringService();
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($hasilTesList as $hasilTes) {
            try {
                // Cek apakah ada data diri
                if (!$hasilTes->karirDataDiri) {
                    throw new \Exception("No KarirDataDiri found for hasil_tes ID {$hasilTes->id}");
                }

                $gender = $hasilTes->karirDataDiri->jenis_kelamin;

                // Hitung ulang skor
                $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasilTes->id, $gender);

                // Ambil top 3 kategori berdasarkan peringkat
                $skorKategori = $hasilPerhitungan['skor_kategori'];
                $peringkat = $hasilPerhitungan['peringkat'];

                // Urutkan kategori berdasarkan peringkat (ascending)
                asort($peringkat);
                $top3Kategori = array_slice(array_keys($peringkat), 0, 3, true);

                // Generate interpretasi
                $top3Data = [];
                foreach ($top3Kategori as $kategori) {
                    $top3Data[$kategori] = $skorKategori[$kategori];
                }
                $interpretasi = $scoringService->generateInterpretasi($top3Data);

                // Update database
                DB::beginTransaction();
                $hasilTes->update([
                    'top_1_pekerjaan' => $top3Kategori[0] ?? '',
                    'top_2_pekerjaan' => $top3Kategori[1] ?? '',
                    'top_3_pekerjaan' => $top3Kategori[2] ?? '',
                    'skor_konsistensi' => $hasilPerhitungan['skor_konsistensi'],
                    'interpretasi' => $interpretasi,
                ]);
                DB::commit();

                $successCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                $errors[] = "ID {$hasilTes->id}: " . $e->getMessage();
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Recalculation completed!');
        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Success', $successCount],
                ['❌ Errors', $errorCount],
                ['📊 Total', $totalRecords],
            ]
        );

        // Show errors if any
        if ($errorCount > 0) {
            $this->newLine();
            $this->error('❌ Errors encountered:');
            foreach ($errors as $error) {
                $this->line("  • {$error}");
            }
        }

        return $errorCount > 0 ? 1 : 0;
    }
}
