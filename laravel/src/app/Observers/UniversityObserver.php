<?php

namespace App\Observers;

use App\Models\University;
use Illuminate\Support\Facades\Cache;

class UniversityObserver
{
    /**
     * Handle the University "created" event.
     */
    public function created(University $university): void
    {
        Cache::forget('universities');
    }

    /**
     * Handle the University "updated" event.
     */
    public function updated(University $university): void
    {
        Cache::forget('universities');
    }

    /**
     * Handle the University "deleted" event.
     */
    public function deleted(University $university): void
    {
        Cache::forget('universities');
    }

    /**
     * Handle the University "restored" event.
     */
    public function restored(University $university): void
    {
        Cache::forget('universities');
    }

    /**
     * Handle the University "force deleted" event.
     */
    public function forceDeleted(University $university): void
    {
        Cache::forget('universities');
    }
}
