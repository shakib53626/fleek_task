<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('me',      [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('categories',          [CategoryController::class, 'index' ]);
    Route::post('categories',         [CategoryController::class, 'store' ]);
    Route::put('categories/{id}',     [CategoryController::class, 'update']);
    Route::delete('categories/{id}',  [CategoryController::class, 'delete']);

    Route::get('products',          [ProductController::class, 'index' ]);
    Route::post('products',         [ProductController::class, 'store' ]);
    Route::put('products/{id}',     [ProductController::class, 'update']);
    Route::delete('products/{id}',  [ProductController::class, 'delete']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('users',          [UserController::class, 'index' ]);
    Route::post('users',         [UserController::class, 'store' ]);
    Route::put('users/{id}',     [UserController::class, 'update']);
    Route::delete('users/{id}',  [UserController::class, 'delete']);
});


