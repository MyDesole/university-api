<?php

namespace app\Services;

use Illuminate\Http\JsonResponse;

interface UniversityApiServiceInterface
{
    public function fetchUniversity(string $country): JsonResponse;
}
