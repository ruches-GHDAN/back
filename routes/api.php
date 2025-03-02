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

define('AUTH', 'auth:sanctum');
define('CREATE_ROUTE', '/create');
define('UPDATE_ROUTE', '/update/{id}');
define('DELETE_ROUTE', '/delete/{id}');

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

Route::group(['prefix' => 'user', 'middleware' => AUTH], function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/apiaries', [UserController::class, 'apiaries']);
});

Route::group(['prefix' => 'apiary', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [ApiaryController::class, 'create']);
    Route::patch(UPDATE_ROUTE, [ApiaryController::class, 'update']);
    Route::delete(DELETE_ROUTE, [ApiaryController::class, 'delete']);
    Route::get('/about/{id}', [ApiaryController::class, 'about']);
    Route::get('/nbApiaries/{id}', [ApiaryController::class, 'nbApiaries']);
    Route::get('/nbHives/{id}', [ApiaryController::class, 'nbHives']);
    Route::get('/getAllLocation/{id}', [ApiaryController::class, 'getAllLocation']);
    Route::get('/status/{id}', [ApiaryController::class, 'status']);
    Route::get('/hasSickHive/{id}', [ApiaryController::class, 'hasSickHive']);
    Route::get('/honeyQuantity/{id}', [ApiaryController::class, 'honeyQuantity']);
    Route::post('/recentlyTranshumed/{id}', [ApiaryController::class, 'recentlyTranshumed']);
});

Route::group(['prefix' => 'hive', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [HiveController::class, 'create']);
    Route::patch(UPDATE_ROUTE, [HiveController::class, 'update']);
    Route::delete(DELETE_ROUTE, [HiveController::class, 'delete']);
    Route::get('/about/{id}', [HiveController::class, 'about']);
    Route::get('/isSick/{id}', [HiveController::class, 'isSick']);
    Route::get('/wasSick/{id}', [HiveController::class, 'wasSick']);
});

Route::group(['prefix' => 'harvest', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [HarvestController::class, 'create']);
    Route::patch(UPDATE_ROUTE, [HarvestController::class, 'update']);
    Route::delete(DELETE_ROUTE, [HarvestController::class, 'delete']);
});

Route::group(['prefix' => 'transhumance', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [TranshumanceController::class, 'create']);
    Route::patch(UPDATE_ROUTE, [TranshumanceController::class, 'update']);
    Route::delete(DELETE_ROUTE, [TranshumanceController::class, 'delete']);
});

Route::group(['prefix' => 'disease', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [DiseaseController::class, 'create']);
    Route::patch(UPDATE_ROUTE, [DiseaseController::class, 'update']);
    Route::delete(DELETE_ROUTE, [DiseaseController::class, 'delete']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => AUTH], function () {
    Route::get('/globalDetail/{id}', [DashboardController::class, 'globalDetail']);
});

Route::group(['prefix' => 'weather', 'middleware' => AUTH], function () {
    Route::post('/getWeather', [WeatherController::class, 'getWeather']);
});

Route::group(['prefix' => 'history', 'middleware' => AUTH], function () {
    Route::post(CREATE_ROUTE, [HistoryController::class, 'create']);
    Route::post(UPDATE_ROUTE, [HistoryController::class, 'update']);
    Route::delete(DELETE_ROUTE, [HistoryController::class, 'delete']);
    Route::post('/getHistoryByApiary/{id}', [HistoryController::class, 'getHistoryByApiary']);
    Route::post('/getHistoryByUser/{id}', [HistoryController::class, 'getHistoryByUser']);
});
