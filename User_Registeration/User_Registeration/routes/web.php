<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\UserInfoController;

Route::get('/', [UserInfoController::class, 'create'])->name('index');
Route::post('/', [UserInfoController::class, 'store'])->name('index.store');
Route::post('/validate-username', [UserInfoController::class, 'validateUsername'])->name('validate.username');
Route::post('/validate-email', [UserInfoController::class, 'validateEmail'])->name('validate.email');