<?php

use App\Models\EntryType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
});

test('index returns 200 and shows entry types owned by user', function () {
    $own = EntryType::factory()->for($this->user)->create(['name' => 'snippet']);
    EntryType::factory()->for($this->other)->create(['name' => 'other-type']);

    $response = $this->actingAs($this->user)->get(route('entry-types.index'));

    $response->assertSuccessful();
    $response->assertSee('snippet');
    $response->assertDontSee('other-type');
});

test('index redirects guests to login', function () {
    $this->get(route('entry-types.index'))->assertRedirect(route('login'));
});

test('create returns 200', function () {
    $this->actingAs($this->user)
        ->get(route('entry-types.create'))
        ->assertSuccessful();
});

test('store creates entry type and redirects to index', function () {
    $response = $this->actingAs($this->user)->post(route('entry-types.store'), [
        'name' => 'question',
        'color' => '#6366f1',
        'icon' => 'question-mark',
    ]);

    $response->assertRedirect(route('entry-types.index'));
    $this->assertDatabaseHas('entry_types', [
        'user_id' => $this->user->id,
        'name' => 'question',
        'color' => '#6366f1',
        'icon' => 'question-mark',
    ]);
});

test('store fails validation when name is missing', function () {
    $this->actingAs($this->user)
        ->post(route('entry-types.store'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('edit returns 200 for owner', function () {
    $entryType = EntryType::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->get(route('entry-types.edit', $entryType))
        ->assertSuccessful()
        ->assertSee($entryType->name);
});

test('edit returns 403 for non-owner', function () {
    $entryType = EntryType::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->get(route('entry-types.edit', $entryType))
        ->assertForbidden();
});

test('update changes name and redirects to index', function () {
    $entryType = EntryType::factory()->for($this->user)->create(['name' => 'old-name']);

    $this->actingAs($this->user)
        ->put(route('entry-types.update', $entryType), [
            'name' => 'new-name',
            'color' => null,
            'icon' => null,
        ])
        ->assertRedirect(route('entry-types.index'));

    expect($entryType->fresh()->name)->toBe('new-name');
});

test('update returns 403 for non-owner', function () {
    $entryType = EntryType::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->put(route('entry-types.update', $entryType), ['name' => 'hacked'])
        ->assertForbidden();
});

test('destroy deletes entry type and redirects to index', function () {
    $entryType = EntryType::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->delete(route('entry-types.destroy', $entryType))
        ->assertRedirect(route('entry-types.index'));

    $this->assertDatabaseMissing('entry_types', ['id' => $entryType->id]);
});

test('destroy returns 403 for non-owner', function () {
    $entryType = EntryType::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->delete(route('entry-types.destroy', $entryType))
        ->assertForbidden();

    $this->assertDatabaseHas('entry_types', ['id' => $entryType->id]);
});
