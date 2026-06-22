<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('authenticated user sees logout button and can logout', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/dashboard')
            ->assertSee('Logout')
            ->press('Logout')
            ->assertPathIs('/login');
    });
});

test('after logout user cannot access protected pages', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertSee('Dashboard')
            ->press('Logout')
            ->assertPathIs('/login')
            ->assertSee('Email Address');
    });
});

test('guest browsing dashboard is redirected to login', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/dashboard')
            ->assertPathIs('/login');
    });
});
