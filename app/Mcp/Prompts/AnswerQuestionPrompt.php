<?php

namespace App\Mcp\Prompts;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Prompts\Argument;

#[Description('Find an unanswered question entry matching the query, compose a thorough answer, and store it as a response using create_response.')]
class AnswerQuestionPrompt extends Prompt
{
    /**
     * Handle the prompt request.
     *
     * @return array<int, \Laravel\Mcp\Response>
     */
    public function handle(Request $request): array
    {
        $validated = $request->validate([
            'query' => 'required|string|max:500',
        ], [
            'query.required' => 'You must provide a query describing the question topic to look for.',
        ]);

        $query = $validated['query'];

        return [
            Response::text(
                "You are a knowledge base assistant. Follow these steps:\n\n".
                "1. Call `search_entries` with keyword=\"{$query}\" to find relevant entries.\n".
                "2. From the results, identify entries that look like unanswered questions (no responses yet or question-style titles).\n".
                "3. Pick the most relevant entry and call `get_entry` with `with_responses=true` to check if it already has answers.\n".
                "4. If the entry has no responses, compose a thorough, accurate answer in markdown.\n".
                "5. Call `create_response` with the entry_id and your answer content to store it.\n".
                '6. Report back what entry you answered and a summary of your answer.'
            )->asAssistant(),
            Response::text("Find and answer a knowledge base question about: {$query}"),
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
                name: 'query',
                description: 'The topic or keywords describing the question you want to find and answer.',
                required: true,
            ),
        ];
    }
}
