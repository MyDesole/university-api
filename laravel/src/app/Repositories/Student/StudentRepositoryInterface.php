<?php

namespace app\Repositories\Student;

use App\Models\Student;
use Illuminate\Support\Collection;

interface StudentRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): Student;
    public function create(array $data): Student;
    public function update(int $id, array $data): bool;
    public function deleteById(int $id): bool;
}
