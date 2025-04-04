<?php

namespace app\Repositories\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LessonRepository implements LessonRepositoryInterface
{
    public function create(array $data): Lesson
    {
        return Lesson::create($data);
    }

    public function getAll(): Collection
    {
        return Cache::remember('lessons', 3600, function () {
            return Lesson::with('childrenRecursive', 'parent', 'children')->whereNull('parent_id')->get();
        });
    }
}
