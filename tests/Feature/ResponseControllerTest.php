<?php

use App\Models\Entry;
use App\Models\Response;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a response and redirects to entry show', function () {
    $user = User::factory()->create();
    $entry = Entry::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('entries.responses.store', $entry), ['content' => 'My response content'])
        ->assertRedirect(route('entries.show', $entry))
        ->assertSessionHas('success');

    expect($entry->responses()->count())->toBe(1)
        ->and($entry->responses()->first()->content)->toBe('My response content')
        ->and($entry->responses()->first()->user_id)->toBe($user->id);
});

it('validates content is required on store', function () {
    $user = User::factory()->create();
    $entry = Entry::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('entries.responses.store', $entry), ['content' => ''])
        ->assertSessionHasErrors('content');
});

it('returns 403 on store when entry belongs to another user', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $entry = Entry::factory()->for($owner)->create();

    $this->actingAs($other)
        ->post(route('entries.responses.store', $entry), ['content' => 'Sneaky'])
        ->assertForbidden();
});

it('destroys a response and redirects to entry show', function () {
    $user = User::factory()->create();
    $entry = Entry::factory()->for($user)->create();
    $response = Response::factory()->for($entry)->for($user)->create();

    $this->actingAs($user)
        ->delete(route('entries.responses.destroy', [$entry, $response]))
        ->assertRedirect(route('entries.show', $entry))
        ->assertSessionHas('success');

    expect($entry->responses()->count())->toBe(0);
});

it('returns 403 on destroy when entry belongs to another user', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $entry = Entry::factory()->for($owner)->create();
    $response = Response::factory()->for($entry)->for($owner)->create();

    $this->actingAs($other)
        ->delete(route('entries.responses.destroy', [$entry, $response]))
        ->assertForbidden();
});

it('returns 404 on destroy when response does not belong to entry', function () {
    $user = User::factory()->create();
    $entry = Entry::factory()->for($user)->create();
    $otherEntry = Entry::factory()->for($user)->create();
    $response = Response::factory()->for($otherEntry)->for($user)->create();

    $this->actingAs($user)
        ->delete(route('entries.responses.destroy', [$entry, $response]))
        ->assertNotFound();
});
