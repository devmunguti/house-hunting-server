<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name("login");
    Route::post('/register', [AuthController::class, 'register']);
});
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);  
});