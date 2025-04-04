<?php

namespace app\Repositories\Visit;

use App\Models\Student;

interface VisitRepositoryInterface
{
    public function logStudentVisit(int $universityId, Student $student): void;
    public function getByStudentId(int $studentId): ?array;
    public function getAll(): ?array;
}
