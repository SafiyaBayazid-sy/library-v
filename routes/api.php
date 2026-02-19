<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookCustomerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookRequestController;
use App\Http\Controllers\Api\WaitingListsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ================ PUBLIC ROUTES ================
Route::post('login',    [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout',   [AuthController::class, 'logout']);

// Public for all guest
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);
Route::get('authors', [AuthorController::class, 'index']);
Route::get('authors/{author}', [AuthorController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ================ ADMIN ONLY ROUTES ================
Route::prefix('admin')->middleware(['auth:sanctum', 'user-type:admin'])->group(function () {

    Route::get('customers',           [UserController::class, 'index']);
    Route::get('customer/{customer}', [AuthController::class, 'show']);
    Route::put('update/user/{user}',        [AuthController::class, 'update']);


    Route::apiResource('books',      BookController::class)->except(['index', 'show']);
    Route::apiResource('authors',    AuthorController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    Route::apiResource('book_requests', BookRequestController::class)->only('index', 'show', 'update', 'destroy');
    Route::apiResource('waiting_lists', WaitingListsController::class)->only('index', 'show'); //update

});

// ================ CUSTOMER ONLY ROUTES ================
Route::prefix('customer')->middleware(['auth:sanctum', 'user-type:customer'])->group(function () {

    // Customer Profile
    Route::put('update/{customer}', [AuthController::class, 'updateCustomer']);

    // Book Requests
    Route::apiResource('book_requests', BookRequestController::class);
    Route::get('book_requests/my_request/{id}',         [BookRequestController::class, 'getCustomerRequests']);

    // Waiting Lists
    Route::apiResource('waiting_lists', WaitingListsController::class);
    Route::get('waiting_lists/my_request/{customer}',         [WaitingListsController::class, 'getCustomerRequests']);
    Route::get('waiting_lists/my_requests/{id}',         [WaitingListsController::class, 'show']);
    // Route::delete('waiting_lists/my_requests/{id}',         [WaitingListsController::class, 'destroy']);

    // Book Ratings
    Route::post('ratings',                                 [BookCustomerController::class, 'store']);
    Route::put('ratings',                                  [BookCustomerController::class, 'update']);
    Route::delete('ratings',                               [BookCustomerController::class, 'destroyRate']);
    Route::get('show/ratings',                            [BookCustomerController::class, 'show']);
    Route::get('ratings/my-rate/{customer}',        [BookCustomerController::class, 'getCustomerRate']);
});

// ================ ADMIN & CUSTOMER ROUTES ================

// ['auth:sanctum', 'user-type:admin,customer'] ,this will use in future when add more rule
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated User
    Route::get('auth/me', [AuthController::class, 'me']);

    // Book Ratings
    Route::get('ratings', [BookCustomerController::class, 'index']);
});

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->noContent();
})->middleware('web');
