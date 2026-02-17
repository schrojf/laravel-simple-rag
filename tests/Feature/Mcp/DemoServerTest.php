<?php

use App\Mcp\Servers\DemoServer;
use App\Mcp\Tools\GenerateRandomUuidTool;
use Illuminate\Support\Str;

test('uuid tool with custom version', function () {
    $uuid = Str::uuid7();

    Str::createUuidsUsing(fn () => $uuid);

    try {
        /** @var \Laravel\Mcp\Server\Testing\TestResponse $response */
        $response = DemoServer::tool(GenerateRandomUuidTool::class, [
            'version' => 'v7',
        ]);
    } finally {
        Str::createUuidsNormally();
    }

    $response
        ->assertOk()
        ->assertSee('Newly generated uuid: "'.$uuid.'" with version: v7.')
        ->assertStructuredContent([
            'version' => 'v7',
            'id' => (string) $uuid,
        ]);
});

test('uuid tool with default version', function () {
    $uuid = Str::uuid();

    Str::createUuidsUsing(fn () => $uuid);

    try {
        $response = DemoServer::tool(GenerateRandomUuidTool::class);
    } finally {
        Str::createUuidsNormally();
    }

    $response
        ->assertOk()
        ->assertSee('Newly generated uuid: "'.$uuid.'" with version: v4.')
        ->assertStructuredContent([
            'version' => 'v4',
            'id' => (string) $uuid,
        ]);
});

test('uuid tool with invalid version', function () {
    $response = DemoServer::tool(GenerateRandomUuidTool::class, [
        'version' => 'v9',
    ]);

    $response
        ->assertHasErrors([
            'Invalid version provided.',
        ]);
});
