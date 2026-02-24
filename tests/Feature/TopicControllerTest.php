<?php

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
});

test('index returns 200 and shows topics owned by user', function () {
    Topic::factory()->for($this->user)->create(['name' => 'Programming']);
    Topic::factory()->for($this->other)->create(['name' => 'Other']);

    $response = $this->actingAs($this->user)->get(route('topics.index'));

    $response->assertSuccessful();
    $response->assertSee('Programming');
    $response->assertDontSee('Other');
});

test('index redirects guests to login', function () {
    $this->get(route('topics.index'))->assertRedirect(route('login'));
});

test('create returns 200', function () {
    $this->actingAs($this->user)
        ->get(route('topics.create'))
        ->assertSuccessful();
});

test('store creates topic and redirects to index', function () {
    $response = $this->actingAs($this->user)->post(route('topics.store'), [
        'name' => 'Programming',
        'color' => '#6366f1',
        'icon' => 'code-bracket',
    ]);

    $response->assertRedirect(route('topics.index'));
    $this->assertDatabaseHas('topics', [
        'user_id' => $this->user->id,
        'name' => 'Programming',
        'color' => '#6366f1',
        'icon' => 'code-bracket',
    ]);
});

test('store rejects an unregistered icon name', function () {
    $this->actingAs($this->user)
        ->post(route('topics.store'), ['name' => 'Test', 'icon' => 'not-a-real-icon'])
        ->assertSessionHasErrors('icon');
});

test('store accepts null icon', function () {
    $this->actingAs($this->user)
        ->post(route('topics.store'), ['name' => 'Test', 'icon' => null])
        ->assertRedirect(route('topics.index'));
});

test('update rejects an unregistered icon name', function () {
    $topic = Topic::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->put(route('topics.update', $topic), ['name' => 'Test', 'icon' => 'invalid-icon'])
        ->assertSessionHasErrors('icon');
});

test('store fails validation when name is missing', function () {
    $this->actingAs($this->user)
        ->post(route('topics.store'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('edit returns 200 for owner', function () {
    $topic = Topic::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->get(route('topics.edit', $topic))
        ->assertSuccessful()
        ->assertSee($topic->name);
});

test('edit returns 403 for non-owner', function () {
    $topic = Topic::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->get(route('topics.edit', $topic))
        ->assertForbidden();
});

test('update changes name and redirects to index', function () {
    $topic = Topic::factory()->for($this->user)->create(['name' => 'Old']);

    $this->actingAs($this->user)
        ->put(route('topics.update', $topic), ['name' => 'New', 'color' => null, 'icon' => null])
        ->assertRedirect(route('topics.index'));

    expect($topic->fresh()->name)->toBe('New');
});

test('update returns 403 for non-owner', function () {
    $topic = Topic::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->put(route('topics.update', $topic), ['name' => 'Hacked'])
        ->assertForbidden();
});

test('destroy deletes topic and redirects to index', function () {
    $topic = Topic::factory()->for($this->user)->create();

    $this->actingAs($this->user)
        ->delete(route('topics.destroy', $topic))
        ->assertRedirect(route('topics.index'));

    $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
});

test('destroy returns 403 for non-owner', function () {
    $topic = Topic::factory()->for($this->other)->create();

    $this->actingAs($this->user)
        ->delete(route('topics.destroy', $topic))
        ->assertForbidden();

    $this->assertDatabaseHas('topics', ['id' => $topic->id]);
});
