<?php

use App\Http\Middleware\EnsureTokenIsValid;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/reset-password', function (Request $request) {
    
        return view('auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
        
    })->middleware([EnsureTokenIsValid::class])->name('password.reset');

    Route::get('', function () {
        return view('auth.login');
    })->name('login');
});

Route::view('/', 'landing-page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/profile', 'profile')->name('profile');

    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::post('/logout', function (Request $request) {
    auth()->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::get('/preview-reset-password', function () {
    $user = User::first();

    if (!$user) return "No user found. Please create a user first.";

    $token = Password::getRepository()->create($user);
    
    $notification = new ResetPassword($token);
    
    return $notification->toMail($user)->render();
});

Route::get('/preview-verify-email', function () {
    $user = User::first();

    if (!$user) return "No user found. Please create a user first.";

    $token = Password::getRepository()->create($user);
    
    $notification = new VerifyEmail($token);
    
    return $notification->toMail($user)->render();
});
