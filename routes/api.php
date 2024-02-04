<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProductController;

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

Route::get('/login', [AuthController::class, 'login']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);

        Route::get('/{id}', [ProductController::class, 'show']);

        Route::post('/create', [ProductController::class, 'store']);

        Route::put('/update/{id}', [ProductController::class, 'update']);

        Route::delete('/delete/{id}', [ProductController::class, 'delete']);

        Route::put('/restore/{id}', [ProductController::class, 'restore']);
    });

    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);

        Route::get('/{id}', [EventController::class, 'show']);

        Route::post('/create', [EventController::class, 'store']);

        Route::put('/update/{id}', [EventController::class, 'update']);

        Route::delete('/delete/{id}', [EventController::class, 'delete']);

        Route::put('/restore/{id}', [EventController::class, 'restore']);
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});
