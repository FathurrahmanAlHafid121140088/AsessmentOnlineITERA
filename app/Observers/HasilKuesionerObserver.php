<?php

namespace App\Observers;

use App\Models\HasilKuesioner;
use Illuminate\Support\Facades\Cache;

class HasilKuesionerObserver
{
    /**
     * Handle the HasilKuesioner "created" event.
     */
    public function created(HasilKuesioner $hasilKuesioner): void
    {
        $this->clearAdminCaches();
        $this->clearUserCache($hasilKuesioner->nim);
    }

    /**
     * Handle the HasilKuesioner "updated" event.
     */
    public function updated(HasilKuesioner $hasilKuesioner): void
    {
        $this->clearAdminCaches();
        $this->clearUserCache($hasilKuesioner->nim);
    }

    /**
     * Handle the HasilKuesioner "deleted" event.
     */
    public function deleted(HasilKuesioner $hasilKuesioner): void
    {
        $this->clearAdminCaches();
        $this->clearUserCache($hasilKuesioner->nim);
    }

    /**
     * Handle the HasilKuesioner "restored" event.
     */
    public function restored(HasilKuesioner $hasilKuesioner): void
    {
        $this->clearAdminCaches();
        $this->clearUserCache($hasilKuesioner->nim);
    }

    /**
     * Handle the HasilKuesioner "force deleted" event.
     */
    public function forceDeleted(HasilKuesioner $hasilKuesioner): void
    {
        $this->clearAdminCaches();
        $this->clearUserCache($hasilKuesioner->nim);
    }

    /**
     * Clear all admin dashboard caches
     */
    private function clearAdminCaches(): void
    {
        Cache::forget('mh.admin.user_stats');
        Cache::forget('mh.admin.kategori_counts');
        Cache::forget('mh.admin.total_tes');
        Cache::forget('mh.admin.fakultas_stats');
    }

    /**
     * Clear user-specific cache
     */
    private function clearUserCache(string $nim): void
    {
        Cache::forget("mh.user.{$nim}.test_history");
    }
}
