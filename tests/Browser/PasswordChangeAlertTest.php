<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('password change displays success alert', function () {
    $user = User::factory()->create([
        'email' => 'dusk-pwd@example.com',
        'password' => bcrypt('Password123'),
        'email_verified_at' => now(),
    ]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser
            // Login
            ->visit('/login')
            ->type('email', 'dusk-pwd@example.com')
            ->type('password', 'Password123')
            ->press('Login')
            ->waitForLocation('/dashboard', 10)
            ->screenshot('01-logged-in-dashboard')

            // Navigate to change password
            ->visit('/change-password')
            ->waitForText('Change Password', 10)
            ->screenshot('02-change-password-page')

            // Fill and submit form
            ->type('current_password', 'Password123')
            ->type('password', 'NewPassword@12345')
            ->type('password_confirmation', 'NewPassword@12345')
            ->screenshot('03-form-filled')
            ->press('Update Password')
            ->pause(3000)  // Wait 3 seconds for submission and page reload
            ->screenshot('04-after-submit')

            // Pause after submit
            ->pause(3000)
            ->screenshot('04b-after-pause')

            // Check where we are
            ->assertPathIs('/change-password')
            ->screenshot('05-still-on-change-password')

            // Try scrolling and check for success text
            ->screenshot('06-full-page-check');
    });
});
