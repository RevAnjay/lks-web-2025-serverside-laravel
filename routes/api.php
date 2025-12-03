<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('validation', [ApiController::class, 'storeValidation']);
    Route::get('validations', [ApiController::class, 'getValidation']);
    Route::get('validations-all', [ApiController::class, 'getAllValidation']);
    Route::get('installment_cars', [ApiController::class, 'installmentCars']);
    Route::get('installment_cars/{id}', [ApiController::class, 'installmentCarsId']);
    Route::post('applications', [ApiController::class, 'applications']);
    Route::get('applications', [ApiController::class, 'applicationsList']);
});
