<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiaryController;
use App\Http\Controllers\HiveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\TranshumanceController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\HistoryController;

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
    Route::post('/create', [ApiaryController::class, 'create']);
    Route::patch('/update/{id}', [ApiaryController::class, 'update']);
    Route::delete('/delete/{id}', [ApiaryController::class, 'delete']);
    Route::get('/about/{id}', [ApiaryController::class, 'about']);
    Route::get('/nbApiaries/{id}', [ApiaryController::class, 'nbApiaries']);
    Route::get('/nbHives/{id}', [ApiaryController::class, 'nbHives']);
    Route::get('/getAllLocation/{id}', [ApiaryController::class, 'getAllLocation']);
    Route::get('/status/{id}', [ApiaryController::class, 'status']);
    Route::get('/hasSickHive/{id}', [ApiaryController::class, 'hasSickHive']);
    Route::get('/honeyQuantity/{id}', [ApiaryController::class, 'honeyQuantity']);
    Route::post('/recentlyTranshumed/{id}', [ApiaryController::class, 'recentlyTranshumed']);
});

Route::group(['prefix' => 'hive', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [HiveController::class, 'create']);
    Route::patch('/update/{id}', [HiveController::class, 'update']);
    Route::delete('/delete/{id}', [HiveController::class, 'delete']);
    Route::get('/about/{id}', [HiveController::class, 'about']);
    Route::get('/isSick/{id}', [HiveController::class, 'isSick']);
    Route::get('/wasSick/{id}', [HiveController::class, 'wasSick']);
});

Route::group(['prefix' => 'harvest', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [HarvestController::class, 'create']);
    Route::patch('/update/{id}', [HarvestController::class, 'update']);
    Route::delete('/delete/{id}', [HarvestController::class, 'delete']);
});

Route::group(['prefix' => 'transhumance', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [TranshumanceController::class, 'create']);
    Route::patch('/update/{id}', [TranshumanceController::class, 'update']);
    Route::delete('/delete/{id}', [TranshumanceController::class, 'delete']);
});

Route::group(['prefix' => 'disease', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [DiseaseController::class, 'create']);
    Route::patch('/update/{id}', [DiseaseController::class, 'update']);
    Route::delete('/delete/{id}', [DiseaseController::class, 'delete']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/globalDetail/{id}', [DashboardController::class, 'globalDetail']);
});

Route::group(['prefix' => 'weather', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/getWeather', [WeatherController::class, 'getWeather']);
});

Route::group(['prefix' => 'history', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/create', [HistoryController::class, 'create']);
    Route::post('/update/{id}', [HistoryController::class, 'update']);
    Route::delete('/delete/{id}', [HistoryController::class, 'delete']);
    Route::post('/getHistoryByApiary/{id}', [HistoryController::class, 'getHistoryByApiary']);
    Route::post('/getHistoryByUser/{id}', [HistoryController::class, 'getHistoryByUser']);
});
