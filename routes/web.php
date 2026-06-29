<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('', [LoginController::class, 'show'])->name('login');
});

Route::get('/', [LandingPageController::class, 'show']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');

    Route::get('/change-password', [PasswordController::class, 'change'])->name('change-password');

    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
