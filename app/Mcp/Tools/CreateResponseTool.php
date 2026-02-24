<?php

namespace App\Mcp\Tools;

use App\Actions\LogMcpRequest;
use App\Models\Entry;
use App\Models\Response as ResponseModel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Store a response for an existing entry. Use this to save answers, summaries, or AI-generated content linked to an entry.')]
class CreateResponseTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request, LogMcpRequest $logger): Response
    {
        $logger->log($request, 'tool', class_basename(static::class));

        $validated = $request->validate([
            'entry_id' => 'required|integer',
            'content' => 'required|string',
            'mime_type' => 'nullable|string|max:100',
            'meta' => 'nullable|array',
        ], [
            'entry_id.required' => 'You must provide the entry_id to attach this response to.',
            'content.required' => 'You must provide the response content.',
        ]);

        $user = $request->user();

        $entry = Entry::where('user_id', $user->id)->find($validated['entry_id']);

        if (! $entry) {
            return Response::error("Entry with ID {$validated['entry_id']} not found.");
        }

        $response = ResponseModel::create([
            'entry_id' => $entry->id,
            'user_id' => $user->id,
            'content' => $validated['content'],
            'mime_type' => $validated['mime_type'] ?? 'text/markdown',
            'meta' => $validated['meta'] ?? null,
        ]);

        return Response::text(json_encode([
            'id' => $response->id,
            'entry_id' => $response->entry_id,
            'mime_type' => $response->mime_type,
            'created_at' => $response->created_at?->toDateTimeString(),
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
                ->description('The ID of the entry this response belongs to.')
                ->required(),
            'content' => $schema->string()
                ->description('The response content (markdown by default).')
                ->required(),
            'mime_type' => $schema->string()
                ->description('The MIME type of the content (default: text/markdown).')
                ->nullable(),
            'meta' => $schema->object()
                ->description('Optional arbitrary key-value pairs (e.g. model_name, source_url).')
                ->nullable(),
        ];
    }
}
