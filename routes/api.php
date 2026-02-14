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
Route::middleware(['auth:sanctum', 'user-type:admin'])->group(function () {

    Route::get('admin/customers',           [UserController::class, 'index']);
    // Route::get('admin/customer/{customer}', [AuthController::class, 'show']);
    Route::put('update/user/{user}',        [AuthController::class, 'update']);

    Route::get('book-list',           [BookController::class, 'bookList']);

    Route::apiResource('books',      BookController::class)->except(['index', 'show']);
    Route::apiResource('authors',    AuthorController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    // Route::apiResource('book-requests', BookRequestController::class)->except('store');
    Route::apiResource('waiting-lists', WaitingListsController::class)->except('store');

});

// ================ CUSTOMER ONLY ROUTES ================
Route::middleware(['auth:sanctum', 'user-type:customer'])->group(function () {

    // Customer Profile
    Route::put('update/customer/{customer}', [AuthController::class, 'updateCustomer']);

    // Book Requests
    Route::post('book-requests',                             [BookRequestController::class, 'store']);
    Route::get('book-requests/customer/{customer}',         [BookRequestController::class, 'getCustomerRequests']);

    // Waiting Lists
    Route::post('waiting-lists',                             [WaitingListsController::class, 'store']);
    Route::get('waiting-lists/my-request/{customer}',         [WaitingListsController::class, 'getCustomerRequests']);

    // Book Ratings
    Route::post('ratings',                                 [BookCustomerController::class, 'store']);
    Route::put('ratings',                                  [BookCustomerController::class, 'update']);
    Route::delete('ratings',                               [BookCustomerController::class, 'destroyRate']);
    Route::post('show/ratings',                            [BookCustomerController::class, 'show']);
    Route::get('ratings/my-rate/{customer}',        [BookCustomerController::class, 'getCustomerRate']);

});

// ================ ADMIN & CUSTOMER ROUTES ================
Route::middleware(['auth:sanctum', 'user-type:admin,customer'])->group(function () {
    Route::get('admin/customer/{customer}', [UserController::class, 'show']);

    // Authenticated User
    Route::get('auth/me', [AuthController::class, 'me']);


    // Book Requests
    Route::apiResource('book-requests', BookRequestController::class)->only('index', 'show', 'update','destroy');

    // Waiting Lists
    Route::apiResource('waiting-lists', WaitingListsController::class)->only('index', 'show', 'update');

    // Book Ratings
    Route::get('ratings', [BookCustomerController::class, 'index']);

});

 Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->noContent();
 })->middleware('web');
