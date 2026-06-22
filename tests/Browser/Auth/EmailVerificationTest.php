<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('unverified user sees email verification notice after login', function () {
    $user = User::factory()->unverified()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/email/verify')
            ->assertSee('Verify your email address');
    });
});

test('verified user goes to dashboard after login', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/dashboard')
            ->assertSee('Dashboard');
    });
});

test('resend verification email button shows success message', function () {
    $user = User::factory()->unverified()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->press('Resend Verification Email')
            ->assertPathIs('/email/verify')
            ->assertSee('A new verification link has been sent');
    });
});

test('logout button on verify page works', function () {
    $user = User::factory()->unverified()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/email/verify')
            ->press('Log out')
            ->assertPathIs('/login');
    });
});
