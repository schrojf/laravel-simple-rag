<?php

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\McpLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
});

test('dashboard returns 200 for authenticated user', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertSuccessful();
});

test('dashboard redirects unauthenticated users', function () {
    $this->get(route('dashboard'))
        ->assertRedirect();
});

test('dashboard shows correct entry count', function () {
    $type = EntryType::factory()->for($this->user)->create();
    Entry::factory()->for($this->user)->for($type, 'type')->count(3)->create();

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertSee('3');
});

test('dashboard shows recent entries for current user only', function () {
    $type = EntryType::factory()->for($this->user)->create();
    $myEntry = Entry::factory()->for($this->user)->for($type, 'type')->create(['title' => 'My Entry']);
    $otherEntry = Entry::factory()->for($this->other)->for(
        EntryType::factory()->for($this->other)->create(), 'type'
    )->create(['title' => 'Other Entry']);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertSee('My Entry')
        ->assertDontSee('Other Entry');
});

test('dashboard shows correct MCP log count', function () {
    McpLog::factory()->count(4)->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertSee('4');
});

test('dashboard shows recent MCP calls for current user only', function () {
    McpLog::factory()->create([
        'user_id' => $this->user->id,
        'primitive_name' => 'MyTool',
    ]);
    McpLog::factory()->create([
        'user_id' => $this->other->id,
        'primitive_name' => 'HiddenTool',
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertSee('MyTool')
        ->assertDontSee('HiddenTool');
});
