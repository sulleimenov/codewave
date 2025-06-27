<?php

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