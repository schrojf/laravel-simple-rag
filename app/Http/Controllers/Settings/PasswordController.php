<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PasswordController extends Controller
{
    public function show(): View
    {
        return view('pages.settings.password');
    }
}
