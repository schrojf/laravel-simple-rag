<?php

namespace App\Mcp\Tools;

use App\Actions\LogMcpRequest;
use App\Models\Entry;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Fetch a single knowledge base entry by ID. Optionally include its responses.')]
class GetEntryTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request, LogMcpRequest $logger): Response
    {
        $logger->log($request, 'tool', class_basename(static::class));

        $validated = $request->validate([
            'id' => 'required|integer',
            'with_responses' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $relations = ['type', 'topics'];
        if ($validated['with_responses'] ?? false) {
            $relations[] = 'responses';
        }

        $entry = Entry::with($relations)
            ->where('user_id', $user->id)
            ->find($validated['id']);

        if (! $entry) {
            return Response::error("Entry with ID {$validated['id']} not found.");
        }

        $result = [
            'id' => $entry->id,
            'title' => $entry->title,
            'content' => $entry->content,
            'type' => $entry->type ? ['id' => $entry->type->id, 'name' => $entry->type->name] : null,
            'topics' => $entry->topics->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])->values(),
            'meta' => $entry->meta,
            'token_estimate' => $entry->token_estimate,
            'created_at' => $entry->created_at?->toDateTimeString(),
            'updated_at' => $entry->updated_at?->toDateTimeString(),
        ];

        if ($validated['with_responses'] ?? false) {
            $result['responses'] = $entry->responses->map(fn ($r) => [
                'id' => $r->id,
                'content' => $r->content,
                'mime_type' => $r->mime_type,
                'created_at' => $r->created_at?->toDateTimeString(),
            ])->values();
        }

        return Response::text(json_encode($result, JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('The ID of the entry to fetch.')
                ->required(),
            'with_responses' => $schema->boolean()
                ->description('Whether to include the entry\'s responses in the result.')
                ->nullable(),
        ];
    }
}
