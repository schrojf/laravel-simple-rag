<?php

use App\Actions\SeedDefaultUserContent;
use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Topic;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('seeds default entry types for the user', function () {
    $user = User::factory()->create();

    app(SeedDefaultUserContent::class)->seed($user);

    expect(EntryType::where('user_id', $user->id)->pluck('name')->sort()->values()->all())
        ->toBe(['Article', 'Note', 'Reference', 'Snippet']);
});

it('seeds default topics for the user', function () {
    $user = User::factory()->create();

    app(SeedDefaultUserContent::class)->seed($user);

    expect(Topic::where('user_id', $user->id)->pluck('name')->sort()->values()->all())
        ->toBe(['AI & Machine Learning', 'General', 'Productivity', 'Programming']);
});

it('seeds default entries for the user', function () {
    $user = User::factory()->create();

    app(SeedDefaultUserContent::class)->seed($user);

    expect(Entry::where('user_id', $user->id)->count())->toBe(3);
});

it('attaches topics to seeded entries', function () {
    $user = User::factory()->create();

    app(SeedDefaultUserContent::class)->seed($user);

    $ragEntry = Entry::where('user_id', $user->id)
        ->where('title', 'What Is Retrieval-Augmented Generation (RAG)?')
        ->with('topics')
        ->first();

    expect($ragEntry->topics)->toHaveCount(2);
});

it('does not seed content for other users', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    app(SeedDefaultUserContent::class)->seed($userA);

    expect(Entry::where('user_id', $userB->id)->count())->toBe(0);
    expect(EntryType::where('user_id', $userB->id)->count())->toBe(0);
    expect(Topic::where('user_id', $userB->id)->count())->toBe(0);
});
