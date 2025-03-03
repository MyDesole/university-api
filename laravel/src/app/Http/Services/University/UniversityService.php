<?php

namespace App\Http\Services\University;

use App\Http\Resources\UniversityCollection;
use App\Http\Resources\UniversityResource;
use App\Models\University;
use Illuminate\Support\Facades\Cache;

class UniversityService
{
    public function list()
    {
        $universities =  Cache::remember('universities', 3600,  function () {
            return University::all();
        }) ;

        return UniversityCollection::make($universities);
    }

    public function search($searchTerm) {
        $universities = University::search($searchTerm)->get();
        return UniversityCollection::make($universities);
    }

    public function index() {
        $university = University::orderBy('id', 'desc')->first();
        return UniversityResource::make($university);
    }

    public function show($id) {
        $university = University::findOrFail($id);
        return UniversityResource::make($university);
    }

    public function destroy($id) {
        try {
            University::destroy($id);
            return response()->json(['message' => 'Успешно удалено!'], 204);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store($data)
    {
        try {
            $university = University::create($data);
            return UniversityResource::make($university);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create university.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update($id, $data)
    {
        try {
            $university = University::findOrFail($id);

            $university->update($data);

            return UniversityResource::make($university);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update university.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
