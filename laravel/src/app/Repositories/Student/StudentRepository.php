<?php

namespace app\Repositories\Student;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StudentRepository
{
    public function getAll(): Collection
    {
       return Cache::remember('students', 3600,  function () {
            return  Student::all();
        });
    }

    public function create(array $data): Student
    {
        return Student::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $student = $this->getById($id);
        return $student->update($data);
    }

    public function getById(int $id): Student
    {
        return Student::where('id', $id)->first();
    }

    public function deleteById(int $id): bool
    {
        $student = $this->getById($id);
        return $student->delete();
    }
}
