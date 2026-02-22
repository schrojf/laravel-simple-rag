<?php

use App\Actions\LogMcpRequest;
use App\Mcp\Servers\RagServer;
use App\Mcp\Tools\ListTypesTool;
use App\Mcp\Tools\SearchEntriesTool;
use App\Models\McpLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Mcp\Request;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
});

// LogMcpRequest action

test('LogMcpRequest persists a log record with correct fields', function () {
    $mcpRequest = new Request(arguments: ['keyword' => 'hello'], sessionId: 'sess-abc');

    $logger = new LogMcpRequest;
    $log = $logger->log($mcpRequest, 'tool', 'SearchEntriesTool');

    expect($log)->toBeInstanceOf(McpLog::class)
        ->and($log->primitive_type)->toBe('tool')
        ->and($log->primitive_name)->toBe('SearchEntriesTool')
        ->and($log->session_id)->toBe('sess-abc')
        ->and($log->input)->toBe(['keyword' => 'hello']);
});

test('LogMcpRequest truncates string values longer than 500 characters', function () {
    $longString = str_repeat('a', 600);
    $mcpRequest = new Request(arguments: ['content' => $longString]);

    $logger = new LogMcpRequest;
    $log = $logger->log($mcpRequest, 'tool', 'CreateEntryTool');

    expect($log->input['content'])->toHaveLength(512)
        ->and($log->input['content'])->toEndWith(' [truncated]');
});

test('LogMcpRequest leaves short strings untouched', function () {
    $mcpRequest = new Request(arguments: ['title' => 'Short title']);

    $logger = new LogMcpRequest;
    $log = $logger->log($mcpRequest, 'tool', 'CreateEntryTool');

    expect($log->input['title'])->toBe('Short title');
});

test('LogMcpRequest truncates nested string values recursively', function () {
    $longString = str_repeat('x', 600);
    $mcpRequest = new Request(arguments: ['meta' => ['note' => $longString]]);

    $logger = new LogMcpRequest;
    $log = $logger->log($mcpRequest, 'tool', 'CreateEntryTool');

    expect($log->input['meta']['note'])->toEndWith(' [truncated]');
});

// MCP integration: tool call creates a log

test('calling a tool via RagServer creates a McpLog record', function () {
    RagServer::actingAs($this->user)->tool(ListTypesTool::class, []);

    expect(McpLog::where('user_id', $this->user->id)->count())->toBe(1);

    $log = McpLog::where('user_id', $this->user->id)->first();
    expect($log->primitive_type)->toBe('tool')
        ->and($log->primitive_name)->toBe('ListTypesTool');
});

test('calling a tool with long input stores truncated input', function () {
    $longContent = str_repeat('z', 600);

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, [
        'keyword' => $longContent,
    ]);

    $log = McpLog::where('user_id', $this->user->id)->first();
    expect($log->input['keyword'])->toEndWith(' [truncated]');
});

// McpLogController

test('GET /mcp-logs returns 200 for authenticated user', function () {
    $this->actingAs($this->user)
        ->get(route('mcp-logs.index'))
        ->assertSuccessful();
});

test('GET /mcp-logs redirects unauthenticated users', function () {
    $this->get(route('mcp-logs.index'))
        ->assertRedirect();
});

test('MCP Logs page only shows current user logs', function () {
    McpLog::create([
        'user_id' => $this->user->id,
        'primitive_type' => 'tool',
        'primitive_name' => 'SearchEntriesTool',
        'input' => null,
    ]);

    McpLog::create([
        'user_id' => $this->other->id,
        'primitive_type' => 'tool',
        'primitive_name' => 'HiddenTool',
        'input' => null,
    ]);

    $this->actingAs($this->user)
        ->get(route('mcp-logs.index'))
        ->assertSee('SearchEntriesTool')
        ->assertDontSee('HiddenTool');
});
