# Feature Testing Documentation (Laravel Dusk)

This document summarizes all features tested end-to-end using **Laravel Dusk** on
this boilerplate. Each feature is accompanied with a list of test scenarios and
relevant screenshots.

## Table of Contents

1. [Landing Page](#1-landing-page)
2. [Registration](#2-registration)
3. [Login](#3-login)
4. [Email Verification](#4-email-verification)
5. [Forgot & Reset Password](#5-forgot--reset-password)
6. [Logout & Page Protection](#6-logout--page-protection)
7. [Running Tests](#running-tests)

---

## Test Files Summary

| File | Feature | Scenarios |
| --- | --- | --- |
| `tests/Browser/LandingPageTest.php` | Landing Page | 8 |
| `tests/Browser/Auth/RegistrationTest.php` | Registration | 12 |
| `tests/Browser/Auth/LoginTest.php` | Login | 6 |
| `tests/Browser/Auth/EmailVerificationTest.php` | Email Verification | 4 |
| `tests/Browser/Auth/PasswordResetTest.php` | Forgot & Reset Password | 6 |
| `tests/Browser/Auth/LogoutTest.php` | Logout & Page Protection | 3 |

---

## 1. Landing Page

File: `tests/Browser/LandingPageTest.php`

The main public page displaying a hero section, application name in navbar, and
different navigation links for guests and authenticated users.

![Landing Page](images/landing-page.png)

### Test Scenarios

| Scenario | Expected |
| --- | --- |
| `landing page can be rendered` | Displays "Solid Foundation for Your App" heading. |
| `landing page displays correct heading and description` | Hero heading and description fully displayed. |
| `guest users see login and register links` | Guests see **Log in** and **Register** links. |
| `guest users can navigate to login from landing page` | Clicking **Log in** leads to `/login`. |
| `guest users can navigate to register from landing page` | Clicking **Get Started** leads to `/register`. |
| `authenticated users see dashboard link` | Logged-in users see **Dashboard**, not **Log in**. |
| `authenticated users can navigate to dashboard from landing page` | Clicking **Go to Dashboard** leads to `/dashboard`. |
| `landing page has app name in navbar` | Application name (`config('app.name')`) shown in navbar. |

---

## 2. Registration

File: `tests/Browser/Auth/RegistrationTest.php`

New account registration with **server-side** and **client-side** password strength
validation (JavaScript). On success, user is redirected to email verification page.

![Halaman Registrasi](images/register-page.png)

Example display when password doesn't meet requirements / already taken:

![Registration Validation](images/register-error.png)

### Test Scenarios

| Scenario | Expected |
| --- | --- |
| `registration page can be rendered` | Form displays Full Name, Email, Password, Confirm Password. |
| `user can register with valid data` | Account saved, redirected to `/email/verify`. |
| `registration fails when name is empty` | Message "The name field is required." |
| `registration fails when email is empty` | Message "The email field is required." |
| `registration fails when email format is invalid` | Message "The email field must be a valid email address." |
| `registration fails when email is already taken` | Message "The email has already been taken." |
| `registration fails when password is less than 8 characters` | JS error: minimum 8 characters. |
| `registration shows js error when password has no mixed case` | JS error: must contain uppercase & lowercase. |
| `registration shows js error when password has no numbers` | JS error: must contain a number. |
| `registration shows js error when password has no symbols` | JS error: must contain a symbol. |
| `registration shows js error when password confirmation does not match` | JS error: password confirmation must match. |
| `registration page has login link` | "Login here" link leads to `/login`. |

> Note: Tests mock the `api.pwnedpasswords.com` API so password leak validation
> doesn't call the actual external service.

---

## 3. Login

File: `tests/Browser/Auth/LoginTest.php`

Authentication of registered users. Successful login redirects to `/dashboard`,
while incorrect credentials display an error message.

![Halaman Login](images/login-page.png)

Display when credentials are wrong:

![Login Failed](images/login-error.png)

### Test Scenarios

| Scenario | Expected |
| --- | --- |
| `login page can be rendered` | Form displays Email, Password, Login, Forgot Password. |
| `user can login with valid credentials` | Redirected to `/dashboard`. |
| `login fails with wrong password` | Message "These credentials do not match our records." |
| `login fails with unregistered email` | Message "These credentials do not match our records." |
| `login page has link to register page` | **Register** link leads to `/register`. |
| `login page has link to forgot password` | **Forgot Password?** link leads to `/forgot-password`. |

---

## 4. Email Verification

File: `tests/Browser/Auth/EmailVerificationTest.php`

Users who haven't verified their email are redirected to a verification notice page
after login. Users can resend the verification link or logout.

![Notifikasi Verifikasi Email](images/email-verify-notice.png)

### Skenario yang diuji

| Skenario | Harapan |
| --- | --- |
| `unverified user sees email verification notice after login` | Diarahkan ke `/email/verify`. |
| `verified user goes to dashboard after login` | Diarahkan ke `/dashboard`. |
| `resend verification email button shows success message` | Pesan "A new verification link has been sent". |
| `logout button on verify page works` | Tombol **Log out** mengembalikan ke `/login`. |

---

## 5. Lupa & Reset Password

Berkas: `tests/Browser/Auth/PasswordResetTest.php`

Alur pemulihan password: meminta tautan reset melalui email, lalu menetapkan
password baru menggunakan token yang valid. Notifikasi email di-*fake* selama
pengujian.

![Halaman Lupa Password](images/forgot-password-page.png)

Halaman penetapan password baru (melalui token):

![Halaman Reset Password](images/reset-password-page.png)

### Skenario yang diuji

| Skenario | Harapan |
| --- | --- |
| `forgot password page can be rendered` | Menampilkan form & tombol "Send Instructions". |
| `reset link can be requested with valid email` | Pesan "Link has been sent to your email address". |
| `reset link fails with unregistered email` | Pesan "We can't find a user with that email address". |
| `reset password page loads with valid token` | Form New Password & Confirm New Password tampil. |
| `reset password shows js error with weak password` | Error JS: minimal 8 karakter. |
| `forgot password page has back to login link` | Tautan "Back to log in" mengarah ke `/login`. |

---

## 6. Logout & Proteksi Halaman

Berkas: `tests/Browser/Auth/LogoutTest.php`

Memastikan pengguna dapat keluar dan halaman terproteksi (`/dashboard`) tidak
dapat diakses tanpa autentikasi.

![Dashboard](images/dashboard.png)

### Skenario yang diuji

| Skenario | Harapan |
| --- | --- |
| `authenticated user sees logout button and can logout` | Tombol **Logout** mengembalikan ke `/login`. |
| `after logout user cannot access protected pages` | Setelah logout, halaman login (`Email Address`) tampil. |
| `guest browsing dashboard is redirected to login` | Tamu membuka `/dashboard` dialihkan ke `/login`. |

---

## Cara Menjalankan Pengujian

Prasyarat:

- Aset frontend sudah ter-*build*: `npm run build`
- Database tersedia & ter-migrasi (Dusk memakai trait `DatabaseMigrations`)
- Google Chrome terpasang (ChromeDriver disediakan oleh paket Dusk)

Menjalankan seluruh pengujian Dusk:

```bash
php artisan dusk
```

Menjalankan satu berkas tertentu:

```bash
php artisan dusk tests/Browser/Auth/LoginTest.php
```

Menjalankan berdasarkan nama skenario:

```bash
php artisan dusk --filter="user can login with valid credentials"
```

> **Memperbarui screenshot dokumentasi**: tangkapan layar pada folder
> `docs/dusk/images/` dibuat dengan memanggil `$browser->screenshot()` pada
> tiap halaman fitur. Jalankan kembali skrip penangkap screenshot bila tampilan
> UI berubah agar dokumentasi tetap akurat.
