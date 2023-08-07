<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' =>  'api', 'prefix' => 'auth'], function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::post('/student/{id?}', 'App\Http\Controllers\StudentController@storeOrUpdate');
    Route::post('/students/{id?}', 'App\Http\Controllers\StudentController@showOrSearch');
    
    Route::delete('/destroy/{id}', 'App\Http\Controllers\StudentController@destroy');
});



