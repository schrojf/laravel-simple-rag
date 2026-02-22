<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        $hasUnverifiedEmail = $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail();

        return view('pages.settings.profile', compact('hasUnverifiedEmail'));
    }
}
