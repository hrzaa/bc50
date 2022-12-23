<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;

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

//ROUTE API
Route::group(['middleware' => 'cors'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'authMiddleware:admin,user'], function () {
        // Route::resource('/siswa', SiswaController::class);
        Route::get('/siswa', [SiswaController::class, 'index']);
    });

    Route::group(['middleware' => 'authMiddleware:admin'], function () {
        Route::post('/siswa', [SiswaController::class, 'store']);
        Route::get('/siswa/{id}', [SiswaController::class, 'show']);
        Route::put('/siswa/{id}', [SiswaController::class, 'update']);
        Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);
    });
});
