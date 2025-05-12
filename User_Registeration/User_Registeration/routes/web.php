<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\UserInfoController;

Route::get('/', [UserInfoController::class, 'create'])->name('index');
Route::post('/', [UserInfoController::class, 'store'])->name('index.store');
