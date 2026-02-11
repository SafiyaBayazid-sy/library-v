<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookCustomerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookRequestController;
use App\Http\Controllers\Api\WaitingListsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('login', [AuthController::class , 'login']);
Route::post('register', [AuthController::class , 'register']);
Route::post('logout', [AuthController::class, 'logout']);





   Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    Route::middleware(['auth:sanctum','user-type:admin,customer'])

    ->group(function () {


Route::get('auth/me',[AuthController::class,'me']);

Route::get('admin/customers',[AuthController::class,'index']);
Route::get('admin/customer/{customer}',[AuthController::class,'show']);

Route::put('update/customer/{customer}',[AuthController::class,'updateCustomer']);
Route::put('update/user/{user}',[AuthController::class,'update']);




        Route::apiResource('books', BookController::class);
        Route::apiResource('authors', AuthorController::class);
        Route::apiResource('categories', CategoryController::class);

        Route::apiResource('book-requests', BookRequestController::class);
Route::get('book-requests/customer/{customer}',[BookRequestController::class,'getCustomerRequests']);




Route::apiResource('rate-book' , BookCustomerController::class)->except('destroy');
Route::get('rate-book/customer-rate/{customer}',[BookCustomerController::class,'getCustomerRate']);
Route::delete('rate-book',[BookCustomerController::class,'destroyRate']);
Route::put('rate-book',[BookCustomerController::class,'update']);
Route::post('show/rate-book',[BookCustomerController::class,'show']);

Route::apiResource('waiting-lists',WaitingListsController::class);
Route::get('waiting-lists/customer/{customer}',[WaitingListsController::class,'getCustomerRequests']);


    });





// when i add prefix api
// Route::get('/sanctum/csrf-cookie', function (Request $request) {
//     return response()->noContent();
// })->middleware('web');
