<?php

use App\Http\Controllers\SeederController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [SeederController::class, 'users']);
Route::post('/project-types', [SeederController::class, 'projectTypes']);
Route::post('/text-classification', [SeederController::class, 'textClassification']);
Route::post('/machine-translation', [SeederController::class, 'machineTranslation']);
Route::post('/entity-recognition', [SeederController::class, 'entityRecognition']);
