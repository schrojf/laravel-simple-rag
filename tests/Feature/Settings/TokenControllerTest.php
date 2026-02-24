<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;
use Laravel\Passport\Token;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
});

function withConfirmedPasswordToken(): array
{
    return ['auth.password_confirmed_at' => time()];
}

test('guest is redirected to login', function () {
    $this->get(route('settings.tokens'))->assertRedirect(route('login'));
});

test('show returns 200 for authenticated user', function () {
    $this->actingAs($this->user)
        ->get(route('settings.tokens'))
        ->assertSuccessful();
});

test('show displays notice when no personal access client exists', function () {
    $this->actingAs($this->user)
        ->get(route('settings.tokens'))
        ->assertSuccessful()
        ->assertSee('Personal access client not configured');
});

test('show displays create form when personal access client exists', function () {
    Client::factory()->asPersonalAccessTokenClient()->create();

    $this->actingAs($this->user)
        ->get(route('settings.tokens'))
        ->assertSuccessful()
        ->assertSee('Create token');
});

test('store creates token and redirects with new_token in session', function () {
    Client::factory()->asPersonalAccessTokenClient()->create();

    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPasswordToken())
        ->post(route('settings.tokens.store'), ['name' => 'My Test Token']);

    $response->assertRedirect(route('settings.tokens'));
    $response->assertSessionHas('new_token');
});

test('store validates name is required', function () {
    $this->actingAs($this->user)
        ->withSession(withConfirmedPasswordToken())
        ->post(route('settings.tokens.store'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('store validates name max length', function () {
    $this->actingAs($this->user)
        ->withSession(withConfirmedPasswordToken())
        ->post(route('settings.tokens.store'), ['name' => str_repeat('a', 101)])
        ->assertSessionHasErrors('name');
});

test('destroy revokes token and redirects', function () {
    Client::factory()->asPersonalAccessTokenClient()->create();

    $tokenResult = $this->user->createToken('My Token');

    $response = $this->actingAs($this->user)
        ->withSession(withConfirmedPasswordToken())
        ->delete(route('settings.tokens.destroy', $tokenResult->token->id));

    $response->assertRedirect(route('settings.tokens'));
    $response->assertSessionHas('success', 'Token revoked.');

    expect(Token::find($tokenResult->token->id)->revoked)->toBeTrue();
});

test('destroy returns 404 for token owned by another user', function () {
    Client::factory()->asPersonalAccessTokenClient()->create();

    $otherToken = $this->other->createToken('Other Token');

    $this->actingAs($this->user)
        ->withSession(withConfirmedPasswordToken())
        ->delete(route('settings.tokens.destroy', $otherToken->token->id))
        ->assertNotFound();
});

test('settings sub-nav includes API Tokens tab', function () {
    $this->actingAs($this->user)
        ->get(route('settings.tokens'))
        ->assertSuccessful()
        ->assertSee('API Tokens')
        ->assertSee(route('settings.profile'))
        ->assertSee(route('settings.password'));
});
