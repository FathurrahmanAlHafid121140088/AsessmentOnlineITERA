<?php

namespace App\Observers;

use App\Models\DataDiris;
use Illuminate\Support\Facades\Cache;

class DataDirisObserver
{
    /**
     * Handle the DataDiris "created" event.
     */
    public function created(DataDiris $dataDiris): void
    {
        $this->clearDemographicCaches();
    }

    /**
     * Handle the DataDiris "updated" event.
     */
    public function updated(DataDiris $dataDiris): void
    {
        $this->clearDemographicCaches();
    }

    /**
     * Handle the DataDiris "deleted" event.
     */
    public function deleted(DataDiris $dataDiris): void
    {
        $this->clearDemographicCaches();
    }

    /**
     * Handle the DataDiris "restored" event.
     */
    public function restored(DataDiris $dataDiris): void
    {
        $this->clearDemographicCaches();
    }

    /**
     * Handle the DataDiris "force deleted" event.
     */
    public function forceDeleted(DataDiris $dataDiris): void
    {
        $this->clearDemographicCaches();
    }

    /**
     * Clear caches related to demographics
     * (user stats, fakultas stats)
     */
    private function clearDemographicCaches(): void
    {
        Cache::forget('mh.admin.user_stats');
        Cache::forget('mh.admin.fakultas_stats');
    }
}
