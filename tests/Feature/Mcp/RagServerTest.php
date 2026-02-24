<?php

use App\Mcp\Prompts\AnswerQuestionPrompt;
use App\Mcp\Prompts\ScrapeAndStorePrompt;
use App\Mcp\Resources\EntryResource;
use App\Mcp\Servers\RagServer;
use App\Mcp\Tools\AddTopicTool;
use App\Mcp\Tools\CreateEntryTool;
use App\Mcp\Tools\CreateResponseTool;
use App\Mcp\Tools\CreateTopicTool;
use App\Mcp\Tools\GetEntryTool;
use App\Mcp\Tools\GetResponsesTool;
use App\Mcp\Tools\ListTopicsTool;
use App\Mcp\Tools\ListTypesTool;
use App\Mcp\Tools\SearchEntriesTool;
use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Response as ResponseModel;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();
    $this->entryType = EntryType::factory()->for($this->user)->create(['name' => 'Note']);
});

/**
 * Create a resource proxy that sends a concrete URI to the server.
 * Necessary because HasUriTemplate resources always return the template string from uri().
 */
function entryResourceUri(string $uri): EntryResource
{
    return new class($uri) extends EntryResource
    {
        public function __construct(private string $resolvedUri) {}

        /** @return array<string, mixed> */
        public function toMethodCall(): array
        {
            return ['uri' => $this->resolvedUri];
        }
    };
}

// --- search_entries ---

test('search_entries returns user entries only', function () {
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'My Entry']);
    Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create(['title' => 'Other Entry']);

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, [])
        ->assertOk()
        ->assertSee('My Entry')
        ->assertDontSee('Other Entry');
});

test('search_entries filters by keyword', function () {
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Laravel Tips']);
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'PHP Basics']);

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, ['keyword' => 'Laravel'])
        ->assertOk()
        ->assertSee('Laravel Tips')
        ->assertDontSee('PHP Basics');
});

test('search_entries includes responses_count in results', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Has Responses']);
    ResponseModel::factory()->for($entry)->for($this->user)->createMany(3);

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, [])
        ->assertOk()
        ->assertSee('"responses_count": 3');
});

test('search_entries filters entries without responses when without_responses is true', function () {
    $withResponse = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Has Response']);
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'No Response']);
    ResponseModel::factory()->for($withResponse)->for($this->user)->create();

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, ['without_responses' => true])
        ->assertOk()
        ->assertSee('No Response')
        ->assertDontSee('Has Response');
});

test('search_entries filters by type_id', function () {
    $typeB = EntryType::factory()->for($this->user)->create();
    Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Type A Entry']);
    Entry::factory()->for($this->user)->for($typeB, 'type')->create(['title' => 'Type B Entry']);

    RagServer::actingAs($this->user)->tool(SearchEntriesTool::class, ['type_id' => $this->entryType->id])
        ->assertOk()
        ->assertSee('Type A Entry')
        ->assertDontSee('Type B Entry');
});

// --- get_entry ---

test('get_entry returns entry details', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create(['title' => 'Detailed Entry']);

    RagServer::actingAs($this->user)->tool(GetEntryTool::class, ['id' => $entry->id])
        ->assertOk()
        ->assertSee('Detailed Entry');
});

test('get_entry returns error for another user entry', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    RagServer::actingAs($this->user)->tool(GetEntryTool::class, ['id' => $entry->id])
        ->assertHasErrors();
});

test('get_entry includes responses when requested', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();
    ResponseModel::factory()->for($entry)->for($this->user)->create(['content' => 'My Answer']);

    RagServer::actingAs($this->user)->tool(GetEntryTool::class, [
        'id' => $entry->id,
        'with_responses' => true,
    ])->assertOk()->assertSee('My Answer');
});

// --- get_responses ---

test('get_responses returns entry responses', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();
    ResponseModel::factory()->for($entry)->for($this->user)->create(['content' => 'Response content here']);

    RagServer::actingAs($this->user)->tool(GetResponsesTool::class, ['entry_id' => $entry->id])
        ->assertOk()
        ->assertSee('Response content here');
});

test('get_responses returns error for another user entry', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    RagServer::actingAs($this->user)->tool(GetResponsesTool::class, ['entry_id' => $entry->id])
        ->assertHasErrors();
});

// --- list_types ---

test('list_types returns user entry types only', function () {
    EntryType::factory()->for($this->other)->create(['name' => 'OtherType']);

    RagServer::actingAs($this->user)->tool(ListTypesTool::class, [])
        ->assertOk()
        ->assertSee('Note')
        ->assertDontSee('OtherType');
});

// --- list_topics ---

test('list_topics returns user topics only', function () {
    Topic::factory()->for($this->user)->create(['name' => 'My Topic']);
    Topic::factory()->for($this->other)->create(['name' => 'Other Topic']);

    RagServer::actingAs($this->user)->tool(ListTopicsTool::class, [])
        ->assertOk()
        ->assertSee('My Topic')
        ->assertDontSee('Other Topic');
});

// --- create_entry ---

test('create_entry creates and returns new entry', function () {
    RagServer::actingAs($this->user)->tool(CreateEntryTool::class, [
        'title' => 'New Entry Title',
        'content' => '# Hello',
        'type_id' => $this->entryType->id,
    ])->assertOk()->assertSee('New Entry Title');

    $this->assertDatabaseHas('entries', ['title' => 'New Entry Title', 'user_id' => $this->user->id]);
});

test('create_entry errors for invalid type_id', function () {
    RagServer::actingAs($this->user)->tool(CreateEntryTool::class, [
        'title' => 'New Entry',
        'content' => '# Hello',
        'type_id' => 99999,
    ])->assertHasErrors();
});

test('create_entry attaches topics', function () {
    $topic = Topic::factory()->for($this->user)->create();

    RagServer::actingAs($this->user)->tool(CreateEntryTool::class, [
        'title' => 'Tagged Entry',
        'content' => '# Content',
        'type_id' => $this->entryType->id,
        'topic_ids' => [$topic->id],
    ])->assertOk();

    $entry = Entry::where('title', 'Tagged Entry')->first();
    expect($entry->topics->pluck('id'))->toContain($topic->id);
});

// --- create_response ---

test('create_response stores response for an entry', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();

    RagServer::actingAs($this->user)->tool(CreateResponseTool::class, [
        'entry_id' => $entry->id,
        'content' => 'This is the answer.',
    ])->assertOk();

    $this->assertDatabaseHas('responses', ['entry_id' => $entry->id, 'content' => 'This is the answer.']);
});

test('create_response errors for another user entry', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    RagServer::actingAs($this->user)->tool(CreateResponseTool::class, [
        'entry_id' => $entry->id,
        'content' => 'Injected answer',
    ])->assertHasErrors();
});

// --- create_topic ---

test('create_topic creates and returns new topic', function () {
    RagServer::actingAs($this->user)->tool(CreateTopicTool::class, ['name' => 'New Topic'])
        ->assertOk()
        ->assertSee('New Topic');

    $this->assertDatabaseHas('topics', ['name' => 'New Topic', 'user_id' => $this->user->id]);
});

// --- add_topic ---

test('add_topic attaches topic to entry', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create();
    $topic = Topic::factory()->for($this->user)->create();

    RagServer::actingAs($this->user)->tool(AddTopicTool::class, [
        'entry_id' => $entry->id,
        'topic_id' => $topic->id,
    ])->assertOk();

    expect($entry->fresh()->topics->pluck('id'))->toContain($topic->id);
});

test('add_topic errors for another user entry', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();
    $topic = Topic::factory()->for($this->user)->create();

    RagServer::actingAs($this->user)->tool(AddTopicTool::class, [
        'entry_id' => $entry->id,
        'topic_id' => $topic->id,
    ])->assertHasErrors();
});

// --- EntryResource ---

test('entry resource returns entry content', function () {
    $entry = Entry::factory()->for($this->user)->for($this->entryType, 'type')->create([
        'title' => 'Resource Entry',
        'content' => 'Some knowledge content.',
    ]);

    RagServer::actingAs($this->user)
        ->resource(entryResourceUri("entry://entries/{$entry->id}"))
        ->assertOk()
        ->assertSee('Resource Entry')
        ->assertSee('Some knowledge content.');
});

test('entry resource errors for another user entry', function () {
    $entry = Entry::factory()->for($this->other)->for(EntryType::factory()->for($this->other), 'type')->create();

    RagServer::actingAs($this->user)
        ->resource(entryResourceUri("entry://entries/{$entry->id}"))
        ->assertHasErrors();
});

// --- Prompts ---

test('answer_question prompt returns messages referencing the query', function () {
    RagServer::actingAs($this->user)->prompt(AnswerQuestionPrompt::class, ['query' => 'laravel routing'])
        ->assertOk()
        ->assertSee('laravel routing');
});

test('scrape_and_store prompt returns messages referencing the url', function () {
    RagServer::actingAs($this->user)->prompt(ScrapeAndStorePrompt::class, ['url' => 'https://example.com'])
        ->assertOk()
        ->assertSee('https://example.com');
});
