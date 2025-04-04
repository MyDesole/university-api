<?php

namespace app\Providers;

use app\Repositories\Lesson\LessonRepository;
use app\Repositories\Lesson\LessonRepositoryInterface;
use app\Repositories\Student\StudentRepository;
use app\Repositories\Student\StudentRepositoryInterface;
use app\Repositories\University\UniversityRepository;
use app\Repositories\University\UniversityRepositoryInterface;
use app\Repositories\Visit\VisitRepository;
use app\Repositories\Visit\VisitRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(UniversityRepositoryInterface::class, UniversityRepository::class);
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);
    }

}
