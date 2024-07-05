<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/user',function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/user/register',[App\Http\Controllers\Api\AuthController::class,'userRegister']);

//restaurant res
Route::post('/restaurant/register', [App\Http\Controllers\Api\AuthController::class,'restaurantRegister']);

route::post('/driver/register', [App\Http\Controllers\Api\AuthController::class,'driverRegister']);

route::post('/driver/login', [App\Http\Controllers\Api\AuthController::class,'driverLogin']);

route::post('/logout',[App\Http\Controllers\Api\AuthController::class,'logout']);


//update latlog

route::post('/user/update', [App\Http\Controllers\Api\AuthController::class,'updateLatlog'])->middleware('auth:sanctum');

Route::get('/restaurant',[App\Http\Controllers\Api\AuthController::class, 'getRestaurant']);
Route::get('/order',[App\Http\Controllers\Api\AuthController::class, 'createOrder'])->Middleware('auth:sanctum');
Route ::put('/order/user/update-status/{id}',[App\Http\Controllers\Api\OrderController::class,'updatePurchaseStatus'])->Middleware('auth:sanctum');
Route::put('/order/user',[App\Http\Controllers\Api\OrderController::class,'OrderHistory'])->Middleware('auth:sanctum');
Route::put('/order/restaurant',[App\Http\Controllers\Api\OrderController::class,'getOrderByStatus'])->Middleware('auth:sanctum');
Route::put('/order/driver',[App\Http\Controllers\Api\OrderController::class,'getOrderByStatusDriver'])->Middleware('auth:sanctum');

Route::put('/order/restaurant/update-status/{id}',[App\Http\Controllers\Api\OrderController::class,'OrderHistory'])->Middleware('auth:sanctum');
Route::put('/order/driver/update-status/{id}',[App\Http\Controllers\Api\OrderController::class,'OrderHistory'])->Middleware('auth:sanctum');


