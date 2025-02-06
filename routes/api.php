<?php

use App\Http\Controllers\ApiaryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', [UserController::class, 'profile']);
});

Route::group(['prefix' => 'apiary', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/about/{id}', [ApiaryController::class, 'about']);
    Route::get('/nbApiaries/{id}', [ApiaryController::class, 'nbApiaries']);
    Route::get('/nbHives/{id}', [ApiaryController::class, 'nbHives']);
    Route::get('/getAllLocation/{id}', [ApiaryController::class, 'getAllLocation']);
    Route::get('/status/{id}', [ApiaryController::class, 'status']);
    Route::get('/hasSickHive/{id}', [ApiaryController::class, 'hasSickHive']);
});

Route::group(['prefix' => 'hive', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/about/{id}', [HiveController::class, 'about']);
    Route::get('/isSick/{id}', [HiveController::class, 'isSick']);
    Route::get('/wasSick/{id}', [HiveController::class, 'wasSick']);
});

