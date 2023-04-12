<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeederController;
use App\Models\Entity;
use App\Models\GeneratedText;
use App\Models\Project;
use App\Models\Sample;
use App\Models\SampleText;
use App\Models\LabelSet;
use App\Models\Label;
use App\Models\Labeling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post(
        '/logout',
        [AuthController::class, 'logout']
    );
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

Route::prefix('/seeders')->group(function () {
    Route::post('/users', [SeederController::class, 'users']);
    Route::post('/text-classification', [SeederController::class, 'textClassification']);
    Route::post('/machine-translation', [SeederController::class, 'machineTranslation']);
    Route::post('/entity-recognition', [SeederController::class, 'entityRecognition']);
});
