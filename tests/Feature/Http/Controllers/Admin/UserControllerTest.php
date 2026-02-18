<?php

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

test('index returns 200 for admin and lists users', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertSee($this->user->name);
});

test('index returns 403 for non-admin', function () {
    $this->actingAs($this->user)
        ->get(route('admin.users.index'))
        ->assertStatus(403);
});

test('show returns 200 for admin and displays user name', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.users.show', $this->user));

    $response->assertStatus(200);
    $response->assertSee($this->user->name);
});

test('show returns 403 for non-admin', function () {
    $this->actingAs($this->user)
        ->get(route('admin.users.show', $this->admin))
        ->assertStatus(403);
});

test('show displays invitation code when user has one', function () {
    $code = InvitationCode::create([
        'code' => 'ABC-DEF-GHJ',
        'used_by' => $this->user->id,
        'used_at' => now(),
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.users.show', $this->user));

    $response->assertStatus(200);
    $response->assertSee('ABC-DEF-GHJ');
});

test('show displays no invitation code message when none exists', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.users.show', $this->user));

    $response->assertStatus(200);
    $response->assertSee('No invitation code');
});
