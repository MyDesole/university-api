<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UniversityCollection;
use App\Models\Student;
use App\Models\University;
use app\Repositories\Student\StudentRepositoryInterface;
use app\Repositories\Visit\VisitRepositoryInterface;
use ClickHouseDB\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;



/**
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="university_id", type="integer", example=1),
 *     @OA\Property(property="course", type="integer", example=1)
 * )
 */



/**
 * @OA\Schema(
 *     schema="StudentVisit",
 *     type="object",
 *     @OA\Property(property="student_id", type="integer", example=1),
 *     @OA\Property(property="university_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01 12:00:00"),
 * )
 */



class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/student/all",
     *     summary="Получить всех студентов",
     *     description="Возвращает список всех студентов",
     *     @OA\Response(
     *         response="200",
     *         description="Список студентов",
     *     )
     * )
     */

    private StudentRepositoryInterface $studentRepository;
    private VisitRepositoryInterface $visitRepository;

    public function __construct(StudentRepositoryInterface $studentRepository, VisitRepositoryInterface $visitRepository) {
        $this->studentRepository = $studentRepository;
        $this->visitRepository = $visitRepository;
    }

    public function index(): StudentCollection
    {
        return StudentCollection::make($this->studentRepository->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/student/store",
     *     summary="Добавить студента",
     *     description="Создает нового студента",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="university_id", type="integer", example=1),
     *             @OA\Property(property="course", type="integer", example=1)
     *         )
     *     ),
     *       @OA\Response(
     *          response="200",
     *          description="Студент",
     *          @OA\JsonContent(
     *              type="object",
     *          )
     *      )
     * )
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        return response()->json(StudentResource::make($this->studentRepository->create($request->validated())));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/student/get/{id}",
     *     summary="Получить студента по ID",
     *     description="Возвращает информацию о студенте по его ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID студента",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Студент не найден"
     *     )
     * )
     */


    public function show(Student $student): JsonResponse
    {
        return response()->json(StudentResource::make($student)) ;
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/student/update/{id}",
     *     summary="Обновить информацию о студенте",
     *     description="Обновляет информацию о студенте по его ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID студента",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="university_id", type="integer", example=1),
     *             @OA\Property(property="course", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Информация о студенте обновлена",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Студент не найден"
     *     )
     * )
     */
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $this->studentRepository->update($id, $request->validated());
        return response()->json(StudentResource::make($this->studentRepository->getById($id)));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/student/delete/{id}",
     *     summary="Удалить студента",
     *     description="Удаляет студента по его ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID студента",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Студент удален",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Успешно!")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Студент не найден"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->studentRepository->deleteById($id);
        return response()->json(['message' => 'Успешно!']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/student/search/{searchTerm}",
     *     summary="Поиск студентов",
     *     description="Ищет студентов по заданному термину",
     *     @OA\Parameter(
     *         name="searchTerm",
     *         in="path",
     *         required=true,
     *         description="Термин для поиска",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Список студентов",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     )
     * )
     */
    public function search(string $searchTerm): JsonResponse
    {
        return response()->json(StudentCollection::make(Student::search($searchTerm)->get())) ;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/student/visit/{studentId}",
     *     summary="Добавить посещение студента",
     *     description="Добавляет запись о посещении студентом университета",
     *     @OA\Parameter(
     *         name="studentId",
     *         in="path",
     *         required=true,
     *         description="ID студента",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="university_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Посещение добавлено",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Успех!")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Неверный student_id"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка при вставке данных"
     *     )
     * )
     */
    public function visit(Request $request, Student $student)
    {
        $this->visitRepository->logStudentVisit($request->input('university_id'), $student);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/student/visit/{studentId?}",
     *     summary="Получить посещения студента",
     *     description="Возвращает список посещений студента. Если studentId не указан, возвращает все посещения",
     *     @OA\Parameter(
     *         name="studentId",
     *         in="path",
     *         required=false,
     *         description="ID студента",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Список посещений",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     )
     * )
     */
    public function getVisits(int $studentId): array
    {

        if (is_numeric($studentId)) {
            return $this->visitRepository->getByStudentId($studentId);
        }
        return $this->visitRepository->getAll();
    }

    // TODO: add tests

}
