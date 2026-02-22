<?php

namespace App\Mcp\Prompts;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Prompts\Argument;

#[Description('Fetch a URL, extract its meaningful content, and store it as a new knowledge base entry using create_entry.')]
class ScrapeAndStorePrompt extends Prompt
{
    /**
     * Handle the prompt request.
     *
     * @return array<int, \Laravel\Mcp\Response>
     */
    public function handle(Request $request): array
    {
        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'type_id' => 'nullable|integer',
        ], [
            'url.required' => 'You must provide the URL to fetch and store.',
            'url.url' => 'The provided value must be a valid URL.',
        ]);

        $url = $validated['url'];
        $typeHint = isset($validated['type_id'])
            ? "Use type_id={$validated['type_id']} when creating the entry."
            : 'Call `list_types` first and pick the most appropriate type_id for the content.';

        return [
            Response::text(
                "You are a web content extractor and knowledge base assistant. Follow these steps:\n\n".
                "1. Fetch the content at: {$url}\n".
                "2. Extract the meaningful text content (title, main body, key facts — skip navigation, ads, footers).\n".
                "3. {$typeHint}\n".
                "4. Call `create_entry` with:\n".
                "   - title: the page title or a concise descriptive title you compose\n".
                "   - content: the extracted content formatted as clean markdown\n".
                "   - type_id: as determined above\n".
                '5. Report the new entry ID, title, and a brief summary of what was stored.'
            )->asAssistant(),
            Response::text("Scrape and store the content from this URL into the knowledge base: {$url}"),
        ];
    }

    /**
     * Get the prompt's arguments.
     *
     * @return array<int, \Laravel\Mcp\Server\Prompts\Argument>
     */
    public function arguments(): array
    {
        return [
            new Argument(
                name: 'url',
                description: 'The URL of the page to fetch and store as a knowledge base entry.',
                required: true,
            ),
            new Argument(
                name: 'type_id',
                description: 'Optional entry type ID to assign. If omitted, list_types will be called to pick one.',
                required: false,
            ),
        ];
    }
}
