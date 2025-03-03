<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UniversityCollection;
use App\Models\Student;
use App\Models\University;
use ClickHouseDB\Client;
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
    public function index()
    {
        $students = Cache::remember('students', 3600,  function () {
            return  Student::all();
        });
        return StudentCollection::make($students);
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
    public function store(StoreStudentRequest $request) {
        $data = $request->validated();
        $student = Student::create($data);
        return response()->json(StudentResource::make($student));
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


    public function show($id) {
        $student = Student::findOrFail($id);
        return StudentResource::make($student);
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
    public function update(UpdateStudentRequest $request, $id) {
        $data = $request->validated();
        $student = Student::where('id', $id);
        $student->update($data);
        return response()->json(StudentResource::make($student->first()));
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
    public function destroy($id) {
        Student::where('id', $id)->delete();
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
    public function search($searchTerm)
    {
        $universities = Student::search($searchTerm)->get();
        return StudentCollection::make($universities);
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
    public function visit(Request $request, $studentId)
    {
        if (!is_numeric($studentId)) {
            return response()->json(['message' => 'Неверный student_id'], 400);
        }

        $universityId = $request->input('university_id');

        if ($universityId == null) {
            $universityId = Student::where('id', '=', $studentId)->first()->university_id ;
        }

        $clickhouse = new Client([
            'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
            'port' => env('CLICKHOUSE_PORT', '8123'),
            'username' => env('CLICKHOUSE_USERNAME', 'mydesole'),
            'password' => env('CLICKHOUSE_PASSWORD', '1008asdt'),
        ]);
        $clickhouse->database('default');

        $data = [
            [
                'id' => 12,
                'student_id' => $studentId,
                'university_id' => $universityId,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]
        ];

        // Вставка данных
        try {
            $clickhouse->insert('student_visits', $data);
            return response()->json(['message' => 'Успех!']);
        } catch (\ClickHouseDB\Exception\TransportException $e) {
            return response()->json(['message' => 'Ошибка подключения к ClickHouse'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка при вставке данных'], 500);
        }
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
    public function getVisits($studentId)
    {
        $clickhouse = new Client([
            'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
            'port' => env('CLICKHOUSE_PORT', '8123'),
            'username' => env('CLICKHOUSE_USERNAME', 'mydesole'),
            'password' => env('CLICKHOUSE_PASSWORD', '1008asdt'),
        ]);

        $clickhouse->database('default');

        $visits = null;

        if (is_numeric($studentId)) {
            $visits = $clickhouse->select('SELECT * FROM student_visits WHERE student_id = :student_id', ['student_id' => $studentId]);        }
        else {
            $visits = $clickhouse->select('SELECT  * FROM student_visits');
        }
        return $visits?->rows();
    }
}
