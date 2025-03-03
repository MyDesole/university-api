<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FetchController extends Controller
{
    public function fetchUniversity($country) {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', 'http://universities.hipolabs.com/search?country=' . urlencode($country));

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                try {
                    Cache:: remember('universities', 3600, function() use ($data) {
                        $fillableFields = ['country', 'name', 'alpha_two_code'];
                        $filteredData = array_map(function ($item) use ($fillableFields) {
                            return array_intersect_key($item, array_flip($fillableFields));
                        }, $data);
                        foreach (array_chunk($filteredData, 100) as $chunk) {
                            University::insert($chunk);
                        }
                    });

                } catch (\Exception $e) {
                    return $e->getMessage();
                }

            } else {
                // Обработка ошибки, если статус не 200
                return ['error' => 'Failed to fetch data. Status code: ' . $response->getStatusCode()];
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Обработка исключений (например, проблемы с сетью)
            return ['error' => 'Request failed: ' . $e->getMessage()];
        }
    }
}
