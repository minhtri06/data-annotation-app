<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});


Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{userId}', [UserController::class, 'show']);
    Route::patch('/{userId}', [UserController::class, 'update']);
    Route::delete('/{userId}', [UserController::class, 'delete']);
});

Route::get('/test', function () {
    return 'Ok';
});

Route::prefix('/projects')->group(function () {
    Route::get('/', function () {
        return Project::all();
    });
    Route::post('/', function (Request $request) {
        return Project::create($request->all());
    });
});

Route::prefix('/project-types')->group(function () {
    Route::get('/', function () {
        return ProjectType::with('projects')->find(1);
    });
    Route::post('/', function (Request $request) {
        return ProjectType::create($request->all());
    });
});
