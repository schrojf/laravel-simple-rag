<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Tool;

class GenerateRandomUuidTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Generate a random UUID with specified version.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response|ResponseFactory
    {
        $version = $request->get('version', 'v4');

        $uuid = match ($version) {
            'v4' => (string) Str::uuid(),
            'v7' => (string) Str::uuid7(),
            default => null,
        };

        if (is_null($uuid)) {
            return Response::error('Invalid version provided.');
        }

        return Response::make(
            Response::text('Newly generated uuid: "'.$uuid.'" with version: '.$version.'.'),
        )->withStructuredContent([
            'version' => $version,
            'id' => $uuid,
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'version' => $schema->string()->enum(['v4', 'v7'])->nullable(),
        ];
    }
}
