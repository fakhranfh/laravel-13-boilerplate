<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('', function () {
        return view('auth.login');
    })->name('login');
});

Route::view('/', 'landing-page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/edit-profile', 'edit-profile')->name('edit-profile');

    Route::view('/change-password', 'change-password')->name('change-password');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
