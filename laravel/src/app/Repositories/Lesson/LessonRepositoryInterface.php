<?php

namespace app\Repositories\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Collection;

interface LessonRepositoryInterface
{
    public function getAll(): Collection;
    public function create(array $data): Lesson;
}
