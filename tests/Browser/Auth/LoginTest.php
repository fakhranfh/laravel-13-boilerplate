<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('login page can be rendered', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->assertSee('Email Address')
            ->assertSee('Password')
            ->assertSee('Login')
            ->assertSee("Don't have an account?")
            ->assertSee('Forgot Password?');
    });
});

test('user can login with valid credentials', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'john@example.com')
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/dashboard')
            ->assertSee('Dashboard');
    });
});

test('login fails with wrong password', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'john@example.com')
            ->type('password', 'wrong-password')
            ->press('Login')
            ->assertPathIs('/login')
            ->assertSee('These credentials do not match our records.');
    });
});

test('login fails with unregistered email', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'nonexistent@example.com')
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/login')
            ->assertSee('These credentials do not match our records.');
    });
});

test('login page has link to register page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->clickLink('Register')
            ->assertPathIs('/register');
    });
});

test('login page has link to forgot password', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->clickLink('Forgot Password?')
            ->assertPathIs('/forgot-password');
    });
});
