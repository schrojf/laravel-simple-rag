<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Laravel\Fortify\Features;
use Throwable;

class TwoFactorController extends Controller
{
    public function show(): View
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), 403);

        $user = auth()->user();

        $twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
        $setupPending = filled($user->two_factor_secret) && is_null($user->two_factor_confirmed_at);

        $qrCodeSvg = null;
        $setupKey = null;
        $recoveryCodes = [];

        if ($setupPending) {
            try {
                $qrCodeSvg = $user->twoFactorQrCodeSvg();
                $setupKey = decrypt($user->two_factor_secret);
            } catch (Throwable) {
                // Empty state shown; user can re-enable
            }
        }

        if ($twoFactorEnabled && filled($user->two_factor_recovery_codes)) {
            try {
                $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
            } catch (Throwable) {
                $recoveryCodes = [];
            }
        }

        return view('pages.settings.two-factor', compact(
            'twoFactorEnabled',
            'setupPending',
            'qrCodeSvg',
            'setupKey',
            'recoveryCodes',
        ));
    }
}
