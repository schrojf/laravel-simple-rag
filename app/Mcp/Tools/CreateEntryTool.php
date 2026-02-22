<?php

namespace App\Mcp\Tools;

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Topic;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new knowledge base entry. Provide a title, markdown content, and an entry type ID. Optionally attach topic IDs.')]
class CreateEntryTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type_id' => 'required|integer',
            'topic_ids' => 'nullable|array',
            'topic_ids.*' => 'integer',
        ], [
            'title.required' => 'You must provide a title for the entry.',
            'content.required' => 'You must provide markdown content for the entry.',
            'type_id.required' => 'You must provide a type_id. Use list_types to get available types.',
        ]);

        $user = $request->user();

        $typeExists = EntryType::where('user_id', $user->id)
            ->where('id', $validated['type_id'])
            ->exists();

        if (! $typeExists) {
            return Response::error("Entry type with ID {$validated['type_id']} not found. Use list_types to get available types.");
        }

        $entry = Entry::create([
            'user_id' => $user->id,
            'type_id' => $validated['type_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if (! empty($validated['topic_ids'])) {
            $topicIds = Topic::where('user_id', $user->id)
                ->whereIn('id', $validated['topic_ids'])
                ->pluck('id');

            $entry->topics()->sync($topicIds);
        }

        return Response::text(json_encode([
            'id' => $entry->id,
            'title' => $entry->title,
            'type_id' => $entry->type_id,
            'created_at' => $entry->created_at?->toDateTimeString(),
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
            'title' => $schema->string()
                ->description('The title of the new entry.')
                ->required(),
            'content' => $schema->string()
                ->description('The markdown content of the entry.')
                ->required(),
            'type_id' => $schema->integer()
                ->description('The ID of the entry type. Use list_types to get available IDs.')
                ->required(),
            'topic_ids' => $schema->array()
                ->description('Optional list of topic IDs to attach to the entry.')
                ->nullable(),
        ];
    }
}
