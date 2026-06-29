<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PasswordController extends Controller
{
    public function change(): View
    {
        return view('change-password');
    }
}
