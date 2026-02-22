<?php

namespace App\Mcp\Resources;

use App\Actions\LogMcpRequest;
use App\Models\Entry;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Support\UriTemplate;

#[Description('Access a knowledge base entry by its ID. Returns the entry title and full markdown content.')]
#[MimeType('text/plain')]
class EntryResource extends Resource implements HasUriTemplate
{
    /**
     * Get the URI template for this resource.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('entry://entries/{id}');
    }

    /**
     * Handle the resource request.
     */
    public function handle(Request $request, LogMcpRequest $logger): Response
    {
        $logger->log($request, 'resource', class_basename(static::class));

        $id = $request->get('id');
        $user = $request->user();

        $entry = Entry::with(['type', 'topics'])
            ->where('user_id', $user->id)
            ->find($id);

        if (! $entry) {
            return Response::error("Entry with ID {$id} not found.");
        }

        $topics = $entry->topics->pluck('name')->implode(', ');
        $type = $entry->type?->name ?? 'Unknown';

        $content = "# {$entry->title}\n\n";
        $content .= "**Type:** {$type}";
        if ($topics) {
            $content .= " | **Topics:** {$topics}";
        }
        $content .= "\n\n---\n\n";
        $content .= $entry->content;

        return Response::text($content);
    }
}
