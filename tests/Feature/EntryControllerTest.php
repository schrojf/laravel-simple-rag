<?php

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
    $this->entryType = EntryType::factory()->for($this->user)->create(['name' => 'snippet']);
});

test('index returns 200 and shows only user entries', function () {
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'My Entry']);
    Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create(['title' => 'Other Entry']);

    $response = $this->actingAs($this->user)->get(route('entries.index'));

    $response->assertSuccessful();
    $response->assertSee('My Entry');
    $response->assertDontSee('Other Entry');
});

test('index redirects guests to login', function () {
    $this->get(route('entries.index'))->assertRedirect(route('login'));
});

test('index filters by type_id', function () {
    $typeA = EntryType::factory()->for($this->user)->create();
    $typeB = EntryType::factory()->for($this->user)->create();
    Entry::factory()->for($this->user)->for($typeA, 'type')->create(['title' => 'Entry A']);
    Entry::factory()->for($this->user)->for($typeB, 'type')->create(['title' => 'Entry B']);

    $response = $this->actingAs($this->user)->get(route('entries.index', ['type_id' => $typeA->id]));

    $response->assertSee('Entry A');
    $response->assertDontSee('Entry B');
});

test('index filters by topic_id', function () {
    $topicX = Topic::factory()->for($this->user)->create();
    $entryWith = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Tagged']);
    $entryWith->topics()->attach($topicX);
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Untagged']);

    $response = $this->actingAs($this->user)->get(route('entries.index', ['topic_id' => $topicX->id]));

    $response->assertSee('Tagged');
    $response->assertDontSee('Untagged');
});

test('create returns 200', function () {
    $this->actingAs($this->user)
        ->get(route('entries.create'))
        ->assertSuccessful();
});

test('store creates entry and redirects to show', function () {
    $response = $this->actingAs($this->user)->post(route('entries.store'), [
        'title' => 'Test Entry',
        'content' => '# Hello',
        'type_id' => $this->entryType->id,
    ]);

    $entry = Entry::where('title', 'Test Entry')->first();
    $response->assertRedirect(route('entries.show', $entry));
    $this->assertDatabaseHas('entries', [
        'user_id' => $this->user->id,
        'title' => 'Test Entry',
        'content' => '# Hello',
        'type_id' => $this->entryType->id,
    ]);
});

test('store creates entry with topics synced', function () {
    $topic = Topic::factory()->for($this->user)->create();

    $this->actingAs($this->user)->post(route('entries.store'), [
        'title' => 'Topical Entry',
        'content' => 'Content here',
        'type_id' => $this->entryType->id,
        'topics' => [$topic->id],
    ]);

    $entry = Entry::where('title', 'Topical Entry')->first();
    expect($entry->topics->pluck('id')->toArray())->toContain($topic->id);
});

test('store fails validation with missing required fields', function () {
    $this->actingAs($this->user)
        ->post(route('entries.store'), [])
        ->assertSessionHasErrors(['title', 'content', 'type_id']);
});

test('show returns 200 for owner', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();

    $this->actingAs($this->user)
        ->get(route('entries.show', $entry))
        ->assertSuccessful()
        ->assertSee($entry->title);
});

test('show returns 403 for non-owner', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    $this->actingAs($this->user)
        ->get(route('entries.show', $entry))
        ->assertForbidden();
});

test('edit returns 200 for owner', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();

    $this->actingAs($this->user)
        ->get(route('entries.edit', $entry))
        ->assertSuccessful();
});

test('edit returns 403 for non-owner', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    $this->actingAs($this->user)
        ->get(route('entries.edit', $entry))
        ->assertForbidden();
});

test('update changes title and content and redirects to show', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Old Title']);

    $this->actingAs($this->user)
        ->put(route('entries.update', $entry), [
            'title' => 'New Title',
            'content' => 'New content',
            'type_id' => $this->entryType->id,
        ])
        ->assertRedirect(route('entries.show', $entry));

    expect($entry->fresh()->title)->toBe('New Title');
});

test('update syncs topics', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();
    $topicA = Topic::factory()->for($this->user)->create();
    $topicB = Topic::factory()->for($this->user)->create();
    $entry->topics()->attach($topicA);

    $this->actingAs($this->user)->put(route('entries.update', $entry), [
        'title' => $entry->title,
        'content' => $entry->content,
        'type_id' => $this->entryType->id,
        'topics' => [$topicB->id],
    ]);

    $entry->refresh();
    expect($entry->topics->pluck('id')->toArray())->toContain($topicB->id);
    expect($entry->topics->pluck('id')->toArray())->not->toContain($topicA->id);
});

test('update returns 403 for non-owner', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    $this->actingAs($this->user)
        ->put(route('entries.update', $entry), ['title' => 'Hacked', 'content' => 'x', 'type_id' => $this->entryType->id])
        ->assertForbidden();
});

test('destroy deletes entry and redirects to index', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();

    $this->actingAs($this->user)
        ->delete(route('entries.destroy', $entry))
        ->assertRedirect(route('entries.index'));

    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
});

test('destroy returns 403 for non-owner', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    $this->actingAs($this->user)
        ->delete(route('entries.destroy', $entry))
        ->assertForbidden();

    $this->assertDatabaseHas('entries', ['id' => $entry->id]);
});
