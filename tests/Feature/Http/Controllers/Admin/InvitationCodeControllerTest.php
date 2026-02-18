<?php

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

test('index returns 200 for admin and lists codes', function () {
    InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $response = $this->actingAs($this->admin)->get(route('admin.invitation-codes.index'));

    $response->assertStatus(200);
    $response->assertSee('ABC-DEF-GHJ');
});

test('index returns 403 for non-admin', function () {
    $this->actingAs($this->user)
        ->get(route('admin.invitation-codes.index'))
        ->assertStatus(403);
});

test('create returns 200 for admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.invitation-codes.create'))
        ->assertStatus(200);
});

test('store with blank code auto-generates and redirects to index', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.invitation-codes.store'), [
        'description' => 'Test batch',
    ]);

    $response->assertRedirect(route('admin.invitation-codes.index'));
    $this->assertDatabaseCount('invitation_codes', 1);

    $code = InvitationCode::first();
    expect($code->code)->toMatch('/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/');
    expect($code->description)->toBe('Test batch');
    expect($code->active)->toBeTrue();
});

test('store with custom code creates record with that code', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.invitation-codes.store'), [
        'code' => 'ABC-DEF-GHJ',
        'description' => 'Manual code',
    ]);

    $response->assertRedirect(route('admin.invitation-codes.index'));
    $this->assertDatabaseHas('invitation_codes', ['code' => 'ABC-DEF-GHJ']);
});

test('store with duplicate code fails validation', function () {
    InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $this->actingAs($this->admin)
        ->post(route('admin.invitation-codes.store'), ['code' => 'ABC-DEF-GHJ'])
        ->assertSessionHasErrors('code');
});

test('show redirects to edit', function () {
    $code = InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $this->actingAs($this->admin)
        ->get(route('admin.invitation-codes.show', $code))
        ->assertRedirect(route('admin.invitation-codes.edit', $code));
});

test('edit returns 200 for admin', function () {
    $code = InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $this->actingAs($this->admin)
        ->get(route('admin.invitation-codes.edit', $code))
        ->assertStatus(200)
        ->assertSee('ABC-DEF-GHJ');
});

test('update changes description and active status and redirects to index', function () {
    $code = InvitationCode::create(['code' => 'ABC-DEF-GHJ', 'active' => true]);

    $response = $this->actingAs($this->admin)->put(route('admin.invitation-codes.update', $code), [
        'description' => 'Updated description',
        'active' => '0',
    ]);

    $response->assertRedirect(route('admin.invitation-codes.index'));

    $code->refresh();
    expect($code->description)->toBe('Updated description');
    expect($code->active)->toBeFalse();
});

test('destroy deletes record and redirects to index', function () {
    $code = InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $this->actingAs($this->admin)
        ->delete(route('admin.invitation-codes.destroy', $code))
        ->assertRedirect(route('admin.invitation-codes.index'));

    $this->assertDatabaseMissing('invitation_codes', ['code' => 'ABC-DEF-GHJ']);
});

test('destroy returns 403 for non-admin', function () {
    $code = InvitationCode::create(['code' => 'ABC-DEF-GHJ']);

    $this->actingAs($this->user)
        ->delete(route('admin.invitation-codes.destroy', $code))
        ->assertStatus(403);

    $this->assertDatabaseHas('invitation_codes', ['code' => 'ABC-DEF-GHJ']);
});
