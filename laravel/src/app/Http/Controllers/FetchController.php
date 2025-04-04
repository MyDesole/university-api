<?php

namespace App\Http\Controllers;

use App\Models\University;
use app\Services\UniversityApiService;
use app\Services\UniversityApiServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FetchController extends Controller
{

    public UniversityApiServiceInterface $apiService;
    public function __construct(UniversityApiServiceInterface $apiService)
    {
        $this->apiService = $apiService;
    }

    public function fetchUniversity($country) {
       return $this->apiService->fetchUniversity($country);
    }

    // TODO: add swagger api docs
    // TODO: add tests
}
