<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;

class CheckValidityStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validity:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check validity status of test results';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Validity Status...');
        $this->newLine();

        $totalTests = DB::table('hasil_kuesioners')->count();
        $withDetails = DB::table('hasil_kuesioners')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('detail_jawabans')
                    ->whereColumn('detail_jawabans.hasil_kuesioner_id', 'hasil_kuesioners.id');
            })->count();
        $withoutDetails = $totalTests - $withDetails;

        $validTests = DB::table('hasil_kuesioners')->where('is_valid', true)->count();
        $invalidTests = DB::table('hasil_kuesioners')->where('is_valid', false)->count();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Test Results', $totalTests],
                ['With Detail Answers (New)', $withDetails],
                ['Without Detail Answers (Old)', $withoutDetails],
                ['Valid Tests', $validTests],
                ['Invalid Tests', $invalidTests],
            ]
        );

        $this->newLine();

        if ($invalidTests > 0) {
            $this->warn("⚠️  Found {$invalidTests} invalid test(s)!");
            $this->info('Use admin dashboard to view details.');
        } else {
            $this->info('✅ All tests with detail answers are valid!');
        }

        if ($withoutDetails > 0) {
            $this->newLine();
            $this->comment("ℹ️  {$withoutDetails} old test(s) without detail answers (before validity feature).");
            $this->comment('   These will show as "N/A" in admin dashboard.');
        }

        return 0;
    }
}
