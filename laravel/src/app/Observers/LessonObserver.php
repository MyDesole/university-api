<?php

namespace App\Observers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Cache;

class LessonObserver
{
    /**
     * Handle the Lesson "created" event.
     */
    public function created(Lesson $lesson): void
    {
        Cache::forget('lessons');
    }

    /**
     * Handle the Lesson "updated" event.
     */
    public function updated(Lesson $lesson): void
    {
        Cache::forget('lessons');

    }

    /**
     * Handle the Lesson "deleted" event.
     */
    public function deleted(Lesson $lesson): void
    {
        Cache::forget('lessons');

    }

    /**
     * Handle the Lesson "restored" event.
     */
    public function restored(Lesson $lesson): void
    {
        Cache::forget('lessons');

    }

    /**
     * Handle the Lesson "force deleted" event.
     */
    public function forceDeleted(Lesson $lesson): void
    {
        Cache::forget('lessons');
    }
}
