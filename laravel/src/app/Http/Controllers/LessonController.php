<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Resources\LessonCollection;
use App\Http\Resources\LessonResource;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Lesson;
use App\Models\Student;
use app\Repositories\Lesson\LessonRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LessonController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/lessons/store",
     *     summary="Создать урок",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="parent_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Урок создан")
     * )
     */

    public LessonRepositoryInterface $lessonRepository;

    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function store(StoreLessonRequest $request)
    {
        return response()->json( LessonResource::make($this->lessonRepository->create($request->validated())) );
    }


    public function index()
    {
        return LessonCollection::make($this->lessonRepository->getAll());
    }

    // TODO: add tests
    // TODO: add swagger api docs


}
