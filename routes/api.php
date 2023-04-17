<?php

use App\Http\Middleware\ConvertRequestFieldsToSnakeCase;
use App\Http\Middleware\ConvertResponseFieldsToCamelCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware([
    ConvertRequestFieldsToSnakeCase::class,
    ConvertResponseFieldsToCamelCase::class
])->group(function () {
    Route::prefix('/auth')->group(base_path('routes/api/auth.php'));
    Route::prefix('/users')->group(base_path('routes/api/users.php'));
    Route::prefix('/me')->middleware('auth:sanctum')->group(base_path('routes/api/me.php'));
    Route::prefix('/projects')->group(base_path('routes/api/projects.php'));
    Route::prefix('/samples')->group(base_path('routes/api/samples.php'));
    Route::prefix('/seeders')->group(base_path('routes/api/seeders.php'));
});

Route::middleware([ConvertRequestFieldsToSnakeCase::class, ConvertResponseFieldsToCamelCase::class])
    ->get('/test', function (Request $request) {
        return 'asdfadfdsaf';
    });
