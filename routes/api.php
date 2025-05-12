<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

require __DIR__ . '/api/admin.php';
require __DIR__ . '/api/operator.php';

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum', 'module'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('changePassword', [AuthController::class, 'changePassword']);
        Route::post('select-module', [AuthController::class, 'selectModule'])->withoutMiddleware(['module']);
    });    
});
