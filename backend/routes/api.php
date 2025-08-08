<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\TaskCategoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/token/create', [AuthController::class, 'createAuthToken']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users/me', [AuthController::class, 'me']);
    Route::get('categories', [TaskCategoryController::class, 'index']);

    Route::prefix('me')->group(function (){
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('tasks', TaskController::class);
    });

});

