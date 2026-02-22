<?php

namespace App\Mcp\Tools;

use App\Actions\LogMcpRequest;
use App\Models\EntryType;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List all entry types defined by the authenticated user. Use the returned IDs when creating entries.')]
class ListTypesTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request, LogMcpRequest $logger): Response
    {
        $logger->log($request, 'tool', class_basename(static::class));

        $types = EntryType::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get()
            ->map(fn (EntryType $type) => [
                'id' => $type->id,
                'name' => $type->name,
                'color' => $type->color,
                'icon' => $type->icon,
            ]);

        return Response::text(json_encode($types, JSON_PRETTY_PRINT));
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
