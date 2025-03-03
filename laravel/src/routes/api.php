<?php

use App\Http\Controllers\UniversityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

});

Route::prefix('')->group(function () {
    Route::prefix('university')->controller(\App\Http\Controllers\UniversityController::class)->group(function () {
       Route::get('/', 'index');
       Route::post('/store',  'store' );
       Route::patch('/update/{id}', 'update');
       Route::get('/all', 'list');
       Route::get('/get/{id}', 'show');
       Route::get('/search/{searchTerm}', 'search');
       Route::delete('/delete/{id}', 'destroy');
    });

    Route::prefix('fetch')->controller(\App\Http\Controllers\FetchController::class)->group(function () {
       Route::get('/university/{country}', 'fetchUniversity');
    });

    Route::prefix('student')->controller(\App\Http\Controllers\StudentController::class)->group(function () {
       Route::get('/all', 'index');
       Route::post('/store', 'store');
       Route::get('get/{id}', 'show');
       Route::patch('/update/{id}', 'update');
       Route::delete('/delete/{id}', 'destroy');
       Route::get('/search/{searchTerm}', 'search');
       Route::post('/visit/{studentId}', 'visit');
       Route::get('/visit/{studentId?}', 'getVisits');
    });

    Route::prefix('lessons')->controller(\App\Http\Controllers\LessonController::class)->group(function () {
        Route::get('/all', 'index');
        Route::post('/store', 'store');
        Route::get('get/{id}', 'show');
        Route::patch('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
        Route::get('/search/{searchTerm}', 'search');
    });

});
