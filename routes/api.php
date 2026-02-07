<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ِAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [ِAuthController::class , 'login']);
Route::post('register', [ِAuthController::class , 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ِAuthController::class, 'logout']);
});



Route::middleware(['auth:sanctum', 'user-type:admin'])->group(function () {
    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('categories', CategoryController::class);
});

   Route::apiResource('books', BookController::class)->except(['store','update','destroy']);
   Route::apiResource('authors', AuthorController::class)->except(['store','update','destroy']);
   Route::apiResource('categories', CategoryController::class)->except(['store','update','destroy']);


Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->noContent();
})->middleware('web');
