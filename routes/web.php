<?php

use Illuminate\Http\Request;
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

    ->name('article.list');
    });

Route::post('/logout', function (Request $request) {
    auth()->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');