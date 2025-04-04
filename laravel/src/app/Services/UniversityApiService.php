<?php

namespace app\Services;

use App\Models\University;
use app\Repositories\University\UniversityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class UniversityApiService implements UniversityRepositoryInterface
{

    public function fetchUniversity(string $country): JsonResponse
    {
        try {
            $universitiesData = $this->fetchUniversitiesFromApi($country);
            $this->storeUniversities($universitiesData);

            return response()->json(['message' => 'Successfully fetched and stored universities'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function fetchUniversitiesFromApi(string $country): array
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'GET',
            'http://universities.hipolabs.com/search?country=' . urlencode($country)
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch universities from API');
        }

        $data = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse API response');
        }

        return $data;
    }

    protected function storeUniversities(array $universitiesData): void
    {
        $filteredData = $this->filterUniversityData($universitiesData);

        Cache::remember('universities', 3600, function() use ($filteredData) {
            foreach (array_chunk($filteredData, 100) as $chunk) {
                University::insert($chunk);
            }
        });
    }

    protected function filterUniversityData(array $universitiesData): array
    {
        $fillableFields = ['country', 'name', 'alpha_two_code'];

        return array_map(function ($item) use ($fillableFields) {
            return array_intersect_key($item, array_flip($fillableFields));
        }, $universitiesData);
    }
}
