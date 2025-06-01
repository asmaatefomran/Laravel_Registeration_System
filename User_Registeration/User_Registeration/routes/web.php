<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\UserInfoController;
use App\Http\Middleware\localization;

Route::get('/', function () {
    return redirect(app()->getLocale());
});

// Language switcher route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
        return redirect('/' . $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::prefix('{locale}')->middleware(localization::class)->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/', [UserInfoController::class, 'create'])->name('index');
    Route::post('/', [UserInfoController::class, 'store'])->name('index.store');
    Route::post('/validate-username', [UserInfoController::class, 'validateUsername'])->name('validate.username');
    Route::post('/validate-email', [UserInfoController::class, 'validateEmail'])->name('validate.email');
});