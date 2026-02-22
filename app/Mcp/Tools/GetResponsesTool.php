<?php

namespace App\Mcp\Tools;

use App\Models\Entry;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Fetch all responses stored for a given entry ID.')]
class GetResponsesTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'entry_id' => 'required|integer',
        ]);

        $user = $request->user();

        $entry = Entry::where('user_id', $user->id)->find($validated['entry_id']);

        if (! $entry) {
            return Response::error("Entry with ID {$validated['entry_id']} not found.");
        }

        $responses = $entry->responses()->latest()->get()->map(fn ($r) => [
            'id' => $r->id,
            'content' => $r->content,
            'mime_type' => $r->mime_type,
            'meta' => $r->meta,
            'created_at' => $r->created_at?->toDateTimeString(),
        ]);

        return Response::text(json_encode($responses, JSON_PRETTY_PRINT));
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
                ->description('The ID of the entry whose responses to retrieve.')
                ->required(),
        ];
    }
}
