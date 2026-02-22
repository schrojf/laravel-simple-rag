<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

/** Helper to bypass the password confirmation middleware in tests. */
function withConfirmedPassword(): array
{
    return ['auth.password_confirmed_at' => time()];
}

test('guest is redirected to login', function () {
    $this->get(route('two-factor.show'))->assertRedirect(route('login'));
});

test('authenticated user sees the disabled state when 2FA is off', function () {
    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->get(route('two-factor.show'));

    $response->assertSuccessful();
    $response->assertSee('Disabled');
    $response->assertSee('Enable 2FA');
});

test('page shows setup-pending state when secret is set but not confirmed', function () {
    $this->user->forceFill([
        'two_factor_secret' => encrypt('BASE32SECRETKEY1234567890'),
        'two_factor_confirmed_at' => null,
    ])->save();

    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->get(route('two-factor.show'));

    $response->assertSuccessful();
    $response->assertSee('Finish setting up two-factor authentication');
    $response->assertSee('Confirm');
    $response->assertSee('Cancel setup');
});

test('page shows enabled state when 2FA is confirmed', function () {
    $recoveryCodes = ['abc-defg-hijk', 'lmno-pqrs-tuvw'];

    $this->user->forceFill([
        'two_factor_secret' => encrypt('BASE32SECRETKEY1234567890'),
        'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->get(route('two-factor.show'));

    $response->assertSuccessful();
    $response->assertSee('Enabled');
    $response->assertSee('Disable 2FA');
    $response->assertSee('2FA Recovery Codes');
});

test('enabling 2FA redirects back to the two-factor settings page', function () {
    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->post(route('two-factor.enable'));

    $response->assertRedirect();

    $this->user->refresh();
    expect($this->user->two_factor_secret)->not->toBeNull();
});

test('disabling 2FA clears the two-factor secret', function () {
    $this->user->forceFill([
        'two_factor_secret' => encrypt('BASE32SECRETKEY1234567890'),
        'two_factor_recovery_codes' => encrypt(json_encode(['abc-defg-hijk'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->delete(route('two-factor.disable'));

    $this->user->refresh();
    expect($this->user->two_factor_secret)->toBeNull();
    expect($this->user->two_factor_confirmed_at)->toBeNull();
});

test('regenerating recovery codes updates the stored codes', function () {
    $originalCodes = encrypt(json_encode(['original-code-1', 'original-code-2']));

    $this->user->forceFill([
        'two_factor_secret' => encrypt('BASE32SECRETKEY1234567890'),
        'two_factor_recovery_codes' => $originalCodes,
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->post(route('two-factor.regenerate-recovery-codes'));

    $this->user->refresh();
    expect($this->user->two_factor_recovery_codes)->not->toBe($originalCodes);
});

test('settings sub-nav includes the Two-Factor tab', function () {
    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPassword())
        ->get(route('two-factor.show'));

    $response->assertSee('Two-Factor');
    $response->assertSee(route('settings.profile'));
    $response->assertSee(route('settings.password'));
});
