<?php

namespace App\Mcp\Tools;

use App\Actions\LogMcpRequest;
use App\Models\Entry;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Search knowledge base entries. Filter by keyword, entry type, or topic. Returns matching entries with their metadata.')]
class SearchEntriesTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request, LogMcpRequest $logger): Response
    {
        $logger->log($request, 'tool', class_basename(static::class));

        $validated = $request->validate([
            'keyword' => 'nullable|string|max:255',
            'type_id' => 'nullable|integer',
            'topic_id' => 'nullable|integer',
            'without_responses' => 'nullable|boolean',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $user = $request->user();

        $query = Entry::query()
            ->where('user_id', $user->id)
            ->withCount('responses')
            ->with(['type', 'topics']);

        if (! empty($validated['keyword'])) {
            $keyword = $validated['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        if (! empty($validated['type_id'])) {
            $query->where('type_id', $validated['type_id']);
        }

        if (! empty($validated['topic_id'])) {
            $query->whereHas('topics', fn ($q) => $q->where('topics.id', $validated['topic_id']));
        }

        if (! empty($validated['without_responses'])) {
            $query->doesntHave('responses');
        }

        $limit = $validated['limit'] ?? 20;
        $entries = $query->latest()->limit($limit)->get();

        $results = $entries->map(fn (Entry $entry) => [
            'id' => $entry->id,
            'title' => $entry->title,
            'type' => $entry->type?->name,
            'topics' => $entry->topics->pluck('name'),
            'content_preview' => str($entry->content)->limit(200)->toString(),
            'token_estimate' => $entry->token_estimate,
            'responses_count' => $entry->responses_count,
            'created_at' => $entry->created_at?->toDateTimeString(),
        ]);

        return Response::text(json_encode($results, JSON_PRETTY_PRINT));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'keyword' => $schema->string()
                ->description('Search keyword to match against entry title and content.')
                ->nullable(),
            'type_id' => $schema->integer()
                ->description('Filter entries by entry type ID.')
                ->nullable(),
            'topic_id' => $schema->integer()
                ->description('Filter entries by topic ID.')
                ->nullable(),
            'without_responses' => $schema->boolean()
                ->description('When true, return only entries that have no responses yet.')
                ->nullable(),
            'limit' => $schema->integer()
                ->description('Maximum number of results to return (1–100, default 20).')
                ->nullable(),
        ];
    }
}
