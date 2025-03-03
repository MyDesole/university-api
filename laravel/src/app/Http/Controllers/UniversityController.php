<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUniversityRequest;
use App\Http\Requests\UpdateUniversityRequest;
use App\Http\Resources\UniversityCollection;
use App\Http\Resources\UniversityResource;
use App\Http\Services\University\UniversityService;
use App\Models\University;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


/**
 * @OA\Schema(
 *     schema="University",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="alpha_two_code", type="string", example="RU"),
 *     @OA\Property(property="county", type="string", example="Russian")
 * )
 */


/**
 * @OA\Info(
 *     title="University API",
 *     version="1.0.0",
 *     description="API для управления университетами и студентами"
 * )
 */


class UniversityController extends Controller
{

    protected UniversityService $service;

    public function __construct(UniversityService $service) {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/university/all",
     *     summary="Получить все университеты",
     *     description="Возвращает список всех университеты",
     *     @OA\Response(
     *         response="200",
     *         description="Список университетов",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     )
     * )
     */

    public function list()
    {
        return $this->service->list();
    }



    public function search($searchTerm) {
       return $this->service->search($searchTerm);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/university/get/{id}",
     *     summary="Получить университет по ID",
     *     description="Возвращает информацию о университете по его ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID университета",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Информация о университете",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Университет не найден"
     *     )
     * )
     */

    public function show($id) {
        return $this->service->show($id);
    }

    public function destroy($id) {
       return $this->service->destroy($id);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/university/store",
     *     summary="Добавить университет",
     *     description="Создает новый университет",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="alpha_two_code", type="string", example="RU"),
     *             @OA\Property(property="county", type="string", example="Russia"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Университет добавлен",
     *     )
     * )
     */

    public function store(StoreUniversityRequest $request) {
        return $this->service->store($request->validated());
    }

    public function update($id, UpdateUniversityRequest $request): UniversityResource|JsonResponse|null
    {
        return $this->service->update($id, $request->validated());
    }
}
