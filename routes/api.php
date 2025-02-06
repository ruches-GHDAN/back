<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiaryController;
use App\Http\Controllers\HiveController;

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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', [UserController::class, 'profile']);
});

Route::group(['prefix' => 'apiary', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [ApiaryController::class, 'create']);
    Route::patch('/update/{id}', [ApiaryController::class, 'update']);
    Route::delete('/delete/{id}', [ApiaryController::class, 'delete']);
});

Route::group(['prefix' => 'hive', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [HiveController::class, 'create']);
    Route::patch('/update/{id}', [HiveController::class, 'update']);
    Route::delete('/delete/{id}', [HiveController::class, 'delete']);
});
