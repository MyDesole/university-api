<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Resources\LessonCollection;
use App\Http\Resources\LessonResource;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Lesson;
use App\Models\Student;
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

    public function store(StoreLessonRequest $request)
    {
        $data = $request->validated();
        $lesson = Lesson::create($data);
        return response()->json( LessonResource::make($lesson) );
    }


    public function index()
    {
        $lessons = Cache::remember('lessons', 3600, function () {
            return Lesson::with('childrenRecursive', 'parent', 'children')->whereNull('parent_id')->get();
        });

        return LessonCollection::make($lessons);
    }
}
