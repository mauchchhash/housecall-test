<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrugController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


/**
 * Auth Routes
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

/**
 * User's Prescribed drugs routes
 */
Route::group(['middleware' => 'auth:sanctum', 'as' => 'Prescribed Drugs'], function () {
    Route::get('users/{user}/drugs', [DrugController::class, 'index']);
    Route::post('users/{user}/drugs', [DrugController::class, 'store']);
    Route::delete('users/{user}/drugs/{rxcui}', [DrugController::class, 'delete']);
});
