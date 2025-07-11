<?php

use App\Http\Controllers\LectionController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('profile', [UserController::class, 'profile']);
Route::post('/login', [AuthController::class, 'login']);

// Предметы
Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/subjects/{id}', [SubjectController::class, 'edit']);
Route::post('/subjects/edit/{subject}', [SubjectController::class, 'update']);
Route::post('/subjects/store', [SubjectController::class, 'store']);
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

// Темы
Route::get('/subjects/{id}/topics', [TopicController::class, 'index']);
Route::get('/subjects/{id}/topic/{topic_id}', [TopicController::class, 'show']);
Route::post('/subjects/{id}/topics/store', [TopicController::class, 'store']);
Route::post('/subjects/{id}/topics/edit', [TopicController::class, 'update']);
Route::delete('/topics/{id}', [TopicController::class, 'destroy']);

// Тесты
//Route::post('/tests', [TestController::class, 'store']);
////Route::get('/tests/{test}/results', [TestController::class, 'submitTest']);
////Route::get('/topics/{topic}/test', [TestController::class, 'getTestByTopic']);
//Route::get('/topics/{topic}/test', [TestController::class, 'getTestByTopic']);
//Route::post('/tests/{test}/results', [TestController::class, 'submitTest']);
//Route::delete('/tests/{test}', [TestController::class, 'delete']);


Route::get('/topics/{topic}/test', [TestController::class, 'getTestByTopic']);
Route::post('/tests/{test}/results', [TestController::class, 'submitTest']);
Route::post('/tests', [TestController::class, 'createTest']);
Route::delete('/tests/{test_id}', [TestController::class, 'deleteTest']);

Route::get('/subjects/{subject}/lection', [LectionController::class, 'show']);
Route::post('/lections', [LectionController::class, 'store']);


Route::get('/topics/{topic_id}/criteria', [CriteriaController::class, 'index']);
Route::post('/topics/{topic_id}/criteria', [CriteriaController::class, 'store']);
Route::delete('/topics/{topic_id}/criteria/{id}', [CriteriaController::class, 'destroy']);

