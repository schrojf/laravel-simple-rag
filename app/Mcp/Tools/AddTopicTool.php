<?php

namespace App\Mcp\Tools;

use App\Models\Entry;
use App\Models\Topic;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Attach an existing topic to an existing entry. Use list_topics and search_entries to find the correct IDs first.')]
class AddTopicTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'entry_id' => 'required|integer',
            'topic_id' => 'required|integer',
        ], [
            'entry_id.required' => 'You must provide the entry_id. Use search_entries to find it.',
            'topic_id.required' => 'You must provide the topic_id. Use list_topics to find it.',
        ]);

        $user = $request->user();

        $entry = Entry::where('user_id', $user->id)->find($validated['entry_id']);
        if (! $entry) {
            return Response::error("Entry with ID {$validated['entry_id']} not found.");
        }

        $topic = Topic::where('user_id', $user->id)->find($validated['topic_id']);
        if (! $topic) {
            return Response::error("Topic with ID {$validated['topic_id']} not found.");
        }

        $entry->topics()->syncWithoutDetaching([$topic->id]);

        return Response::text(json_encode([
            'entry_id' => $entry->id,
            'topic_id' => $topic->id,
            'topic_name' => $topic->name,
            'message' => "Topic \"{$topic->name}\" attached to entry \"{$entry->title}\".",
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
            'entry_id' => $schema->integer()
                ->description('The ID of the entry to tag.')
                ->required(),
            'topic_id' => $schema->integer()
                ->description('The ID of the topic to attach.')
                ->required(),
        ];
    }
}
