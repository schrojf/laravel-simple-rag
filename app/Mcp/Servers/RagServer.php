<?php

namespace App\Mcp\Servers;

use App\Mcp\Prompts\AnswerQuestionPrompt;
use App\Mcp\Prompts\ScrapeAndStorePrompt;
use App\Mcp\Resources\EntryResource;
use App\Mcp\Tools\AddTopicTool;
use App\Mcp\Tools\CreateEntryTool;
use App\Mcp\Tools\CreateResponseTool;
use App\Mcp\Tools\CreateTopicTool;
use App\Mcp\Tools\GetEntryTool;
use App\Mcp\Tools\GetResponsesTool;
use App\Mcp\Tools\ListTopicsTool;
use App\Mcp\Tools\ListTypesTool;
use App\Mcp\Tools\SearchEntriesTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Knowledge Base RAG Server')]
#[Version('1.0.0')]
#[Instructions(<<<'MARKDOWN'
    This server provides tools to manage and query a personal knowledge base.

    You can search, read, and create entries (documents), responses (answers/summaries),
    topics (tags), and entry types. All operations are scoped to the authenticated user.

    Typical workflows:
    - Use `list_types` and `list_topics` to discover available categories and tags.
    - Use `search_entries` to find relevant entries before creating duplicates.
    - Use `create_entry` to store new knowledge, then `add_topic` to tag it.
    - Use `create_response` to store an answer or summary linked to an existing entry.
    - Use the `answer_question` or `scrape_and_store` prompts for guided AI workflows.
    MARKDOWN)]
class RagServer extends Server
{
    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        SearchEntriesTool::class,
        GetEntryTool::class,
        GetResponsesTool::class,
        ListTypesTool::class,
        ListTopicsTool::class,
        CreateEntryTool::class,
        CreateResponseTool::class,
        CreateTopicTool::class,
        AddTopicTool::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        EntryResource::class,
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        AnswerQuestionPrompt::class,
        ScrapeAndStorePrompt::class,
    ];
}
