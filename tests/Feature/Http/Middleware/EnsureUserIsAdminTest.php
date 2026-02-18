<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin user can access admin routes', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/invitation-codes');

    $response->assertStatus(200);
});

test('non-admin authenticated user is blocked with 403', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/invitation-codes');

    $response->assertStatus(403);
});

test('unauthenticated guest is redirected to login', function () {
    $response = $this->get('/admin/invitation-codes');

    $response->assertRedirect(route('login'));
});
