<?php

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.require_invitation' => true]);
});

/** @return array<string, string> */
function registrationData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $overrides);
}

test('registration screen shows invitation code input when enabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Invitation Code');
    $response->assertSee('invitation_code');
});

test('successful registration with valid invitation code', function () {
    $invitation = InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $response = $this->post('/register', registrationData([
        'invitation_code' => 'ABC-DEF-GHJ',
    ]));

    $response->assertRedirect();
    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();
    $invitation->refresh();

    expect($invitation->used_at)->not->toBeNull();
    expect($invitation->used_by)->toBe($user->id);
});

test('registration fails without invitation code field', function () {
    $response = $this->post('/register', registrationData());

    $response->assertSessionHasErrors('invitation_code');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});

test('registration fails with nonexistent invitation code', function () {
    $response = $this->post('/register', registrationData([
        'invitation_code' => 'XXX-XXX-XXX',
    ]));

    $response->assertSessionHasErrors(['invitation_code' => 'Invitation code not found.']);
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});

test('registration fails with inactive invitation code', function () {
    InvitationCode::create(['code' => 'ABC-DEF-GHJ', 'active' => false]);

    $response = $this->post('/register', registrationData([
        'invitation_code' => 'ABC-DEF-GHJ',
    ]));

    $response->assertSessionHasErrors(['invitation_code' => 'Invitation code not found.']);
    $this->assertGuest();
});

test('registration fails with already used invitation code', function () {
    $existingUser = User::factory()->create();
    InvitationCode::create([
        'code' => 'ABC-DEF-GHJ',
        'used_at' => now(),
        'used_by' => $existingUser->id,
    ]);

    $response = $this->post('/register', registrationData([
        'invitation_code' => 'ABC-DEF-GHJ',
    ]));

    $response->assertSessionHasErrors(['invitation_code' => 'Invitation code already used.']);
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});

test('race condition protection prevents double use of same code', function () {
    InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $firstResponse = $this->post('/register', registrationData([
        'invitation_code' => 'ABC-DEF-GHJ',
    ]));

    $firstResponse->assertRedirect();
    $this->assertAuthenticated();

    auth()->logout();

    $secondResponse = $this->post('/register', [
        'name' => 'Second User',
        'email' => 'second@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'invitation_code' => 'ABC-DEF-GHJ',
    ]);

    $secondResponse->assertSessionHasErrors(['invitation_code' => 'Invitation code already used.']);
    $this->assertDatabaseMissing('users', ['email' => 'second@example.com']);
    $this->assertDatabaseCount('users', 1);
});

test('lowercase invitation code is normalized and accepted', function () {
    InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $response = $this->post('/register', registrationData([
        'invitation_code' => 'abc-def-ghj',
    ]));

    $response->assertRedirect();
    $this->assertAuthenticated();
});

test('registration works without invitation code when feature is disabled', function () {
    config(['app.require_invitation' => false]);

    $response = $this->post('/register', registrationData());

    $response->assertRedirect();
    $this->assertAuthenticated();
});
