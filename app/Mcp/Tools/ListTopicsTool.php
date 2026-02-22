<?php

namespace App\Mcp\Tools;

use App\Models\Topic;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List all topics defined by the authenticated user. Use the returned IDs when tagging entries.')]
class ListTopicsTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $topics = Topic::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get()
            ->map(fn (Topic $topic) => [
                'id' => $topic->id,
                'name' => $topic->name,
                'color' => $topic->color,
                'icon' => $topic->icon,
            ]);

        return Response::text(json_encode($topics, JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
