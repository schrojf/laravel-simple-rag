<?php

namespace App\Mcp\Tools;

use App\Models\Topic;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new topic tag. Topics can be attached to entries to organise them by theme or category.')]
class CreateTopicTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ], [
            'name.required' => 'You must provide a name for the topic.',
        ]);

        $topic = Topic::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'color' => $validated['color'] ?? null,
            'icon' => $validated['icon'] ?? null,
        ]);

        return Response::text(json_encode([
            'id' => $topic->id,
            'name' => $topic->name,
            'color' => $topic->color,
            'icon' => $topic->icon,
            'created_at' => $topic->created_at?->toDateTimeString(),
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('The name of the new topic.')
                ->required(),
            'color' => $schema->string()
                ->description('Optional color identifier for the topic (e.g. "blue", "#3b82f6").')
                ->nullable(),
            'icon' => $schema->string()
                ->description('Optional icon identifier for the topic.')
                ->nullable(),
        ];
    }
}
