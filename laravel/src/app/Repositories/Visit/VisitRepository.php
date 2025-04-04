<?php

namespace app\Repositories\Visit;

use App\Models\Student;
use ClickHouseDB\Client;

class VisitRepository implements VisitRepositoryInterface

{
    private Client $clickHouseClient;

    public function __construct(Client $clickHouseClient)
    {
        $this->clickHouseClient = $clickHouseClient;
        $this->clickHouseClient->database('default');
    }

    public function logStudentVisit(int $universityId, Student $student): void
    {
        $data = [
            [
                'id' => $this->generateUniqueId(),
                'student_id' => $student->id,
                'university_id' => $universityId,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]
        ];

        $this->clickHouseClient->insert('student_visits', $data);
    }

    public function getByStudentId(int $studentId): ?array
    {
       $visits = $this->clickHouseClient->select('SELECT * FROM student_visits WHERE student_id = :student_id', ['student_id' => $studentId]);
        return $visits?->rows();
    }

    public function getAll(): ?array
    {
        $visits = $this->clickHouseClient->select('SELECT  * FROM student_visits');
        return $visits?->rows();

    }

    private function generateUniqueId(): int
    {
        return time() . random_int(100, 999);
    }
}
