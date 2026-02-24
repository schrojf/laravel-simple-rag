<?php

namespace App\Actions;

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Response as ResponseModel;
use App\Models\Topic;
use App\Models\User;

class SeedDefaultUserContent
{
    public function seed(User $user): void
    {
        $types = $this->seedEntryTypes($user);
        $topics = $this->seedTopics($user);
        $this->seedEntries($user, $types, $topics);
    }

    /** @return array<string, EntryType> */
    private function seedEntryTypes(User $user): array
    {
        $definitions = [
            ['name' => 'Note',      'color' => '#6366f1', 'icon' => 'pencil'],
            ['name' => 'Article',   'color' => '#0ea5e9', 'icon' => 'document-text'],
            ['name' => 'Reference', 'color' => '#10b981', 'icon' => 'bookmark'],
            ['name' => 'Snippet',   'color' => '#f59e0b', 'icon' => 'code-bracket'],
            ['name' => 'Question',  'color' => '#ec4899', 'icon' => 'information-circle'],
        ];

        $types = [];

        foreach ($definitions as $definition) {
            $types[$definition['name']] = EntryType::create([
                'user_id' => $user->id,
                'name' => $definition['name'],
                'color' => $definition['color'],
                'icon' => $definition['icon'],
            ]);
        }

        return $types;
    }

    /** @return array<string, Topic> */
    private function seedTopics(User $user): array
    {
        $definitions = [
            ['name' => 'General',              'icon' => 'tag'],
            ['name' => 'Programming',          'icon' => 'code-bracket'],
            ['name' => 'AI & Machine Learning', 'icon' => 'star'],
            ['name' => 'Productivity',         'icon' => 'check-circle'],
            ['name' => 'Laravel',              'icon' => 'document'],
            ['name' => 'DevOps',               'icon' => 'folder'],
        ];

        $topics = [];

        foreach ($definitions as $definition) {
            $topics[$definition['name']] = Topic::create([
                'user_id' => $user->id,
                'name' => $definition['name'],
                'icon' => $definition['icon'],
            ]);
        }

        return $topics;
    }

    /**
     * @param  array<string, EntryType>  $types
     * @param  array<string, Topic>  $topics
     */
    private function seedEntries(User $user, array $types, array $topics): void
    {
        $definitions = [
            // ---------------------------------------------------------------
            // Notes
            // ---------------------------------------------------------------
            [
                'type' => 'Note',
                'topics' => ['General'],
                'title' => 'Getting Started with Your Knowledge Base',
                'content' => <<<'MD'
                    # Getting Started

                    Welcome to your personal knowledge base — a place to capture and organise information so your AI assistant can retrieve it when you need it.

                    ## What Are Entries?

                    Each **entry** is a piece of knowledge written in Markdown. You can store:

                    - Notes and ideas
                    - Articles and summaries
                    - Code snippets and references
                    - Questions you want the AI to answer

                    ## Organisation

                    - **Entry Types** categorise what kind of content an entry is (Note, Article, Reference, Snippet, Question)
                    - **Topics** group entries by subject matter

                    ## Tips for Better Retrieval

                    - Keep entries focused on a single idea or topic
                    - Use descriptive titles — they help the AI find the right entry
                    - The token count shown on each entry tells you how much context it uses when queried
                    - Create a **Question** entry for anything you want the AI to research and answer for you
                    MD,
                'responses' => [],
            ],
            [
                'type' => 'Note',
                'topics' => ['AI & Machine Learning', 'General'],
                'title' => 'MCP Server: How LLMs Interact with This App',
                'content' => <<<'MD'
                    # MCP Server — How It Works

                    This app runs an MCP (Model Context Protocol) server at `/mcp/rag`. Your AI assistant connects to it via OAuth2 and can use the following tools and prompts.

                    ## Tools Available to the LLM

                    | Tool | What it does |
                    |------|-------------|
                    | `search_entries` | Search by keyword, type, or topic |
                    | `get_entry` | Fetch a full entry (with optional responses) |
                    | `get_responses` | List all responses for an entry |
                    | `list_types` | List your entry types |
                    | `list_topics` | List your topics |
                    | `create_entry` | Create a new entry |
                    | `create_response` | Store an answer or generated content |
                    | `create_topic` | Create a new topic |
                    | `add_topic` | Tag an entry with a topic |

                    ## Prompts Available

                    - **`answer_question`** — find an unanswered question entry and store an answer
                    - **`scrape_and_store`** — fetch a URL, extract the content, save as an entry

                    ## Resource

                    - **`entry://entries/{id}`** — read any entry by ID directly as a resource

                    ## Typical Workflow

                    1. You create a `Question` entry in the web UI
                    2. You ask your AI assistant to answer questions in your knowledge base
                    3. The LLM uses `search_entries`, finds the question, writes an answer, calls `create_response`
                    4. You review the answer in the web UI
                    MD,
                'responses' => [],
            ],

            // ---------------------------------------------------------------
            // References
            // ---------------------------------------------------------------
            [
                'type' => 'Reference',
                'topics' => ['Programming'],
                'title' => 'Markdown Cheat Sheet',
                'content' => <<<'MD'
                    # Markdown Cheat Sheet

                    All entries are written in **GitHub Flavored Markdown**.

                    ## Headings

                    ```
                    # H1  ## H2  ### H3
                    ```

                    ## Emphasis

                    ```
                    **bold**   _italic_   ~~strikethrough~~
                    ```

                    ## Lists

                    ```
                    - Unordered item
                    1. Ordered item
                    ```

                    ## Code

                    Inline: `code`

                    Fenced block:

                    ```php
                    echo 'Hello, World!';
                    ```

                    ## Links & Images

                    ```
                    [Link text](https://example.com)
                    ![Alt text](image.png)
                    ```

                    ## Tables

                    ```
                    | Column A | Column B |
                    |----------|----------|
                    | Cell 1   | Cell 2   |
                    ```

                    ## Blockquotes

                    ```
                    > Quoted text goes here.
                    ```

                    ## Task Lists

                    ```
                    - [x] Done
                    - [ ] To do
                    ```
                    MD,
                'responses' => [],
            ],
            [
                'type' => 'Reference',
                'topics' => ['Laravel', 'Programming'],
                'title' => 'Laravel Eloquent Relationships Quick Reference',
                'content' => <<<'MD'
                    # Laravel Eloquent Relationships

                    ## One to One

                    ```php
                    // User has one Profile
                    public function profile(): HasOne
                    {
                        return $this->hasOne(Profile::class);
                    }

                    // Profile belongs to User
                    public function user(): BelongsTo
                    {
                        return $this->belongsTo(User::class);
                    }
                    ```

                    ## One to Many

                    ```php
                    public function posts(): HasMany
                    {
                        return $this->hasMany(Post::class);
                    }
                    ```

                    ## Many to Many

                    ```php
                    public function tags(): BelongsToMany
                    {
                        return $this->belongsToMany(Tag::class, 'post_tag');
                    }
                    ```

                    ## Eager Loading (avoid N+1)

                    ```php
                    // Load with relationships
                    User::with(['posts', 'posts.comments'])->get();

                    // Conditional eager loading
                    User::with(['posts' => fn ($q) => $q->where('published', true)])->get();
                    ```

                    ## Common Query Scopes

                    ```php
                    // Has any related
                    Post::has('comments')->get();

                    // Has related matching condition
                    Post::whereHas('comments', fn ($q) => $q->where('approved', true))->get();

                    // Count
                    Post::withCount('comments')->get(); // adds comments_count attribute
                    ```
                    MD,
                'responses' => [],
            ],

            // ---------------------------------------------------------------
            // Snippets
            // ---------------------------------------------------------------
            [
                'type' => 'Snippet',
                'topics' => ['Programming', 'DevOps'],
                'title' => 'Git: Everyday Commands',
                'content' => <<<'MD'
                    # Git: Everyday Commands

                    ## Starting

                    ```bash
                    git init                    # new repo
                    git clone <url>             # clone existing
                    ```

                    ## Staging & Committing

                    ```bash
                    git status                  # what's changed
                    git diff                    # unstaged changes
                    git diff --staged           # staged changes
                    git add <file>              # stage a file
                    git add -p                  # interactive stage (hunk by hunk)
                    git commit -m "message"
                    git commit --amend          # fix last commit message or content
                    ```

                    ## Branching

                    ```bash
                    git branch                  # list branches
                    git checkout -b feature/x   # create and switch
                    git switch main             # switch (modern)
                    git merge feature/x         # merge into current
                    git rebase main             # rebase onto main
                    ```

                    ## Remote

                    ```bash
                    git fetch origin
                    git pull origin main
                    git push origin feature/x
                    git push --force-with-lease # safer force push
                    ```

                    ## Undoing

                    ```bash
                    git restore <file>          # discard working dir changes
                    git restore --staged <file> # unstage
                    git revert <sha>            # new commit that undoes
                    git stash                   # save changes temporarily
                    git stash pop               # restore stashed changes
                    ```

                    ## Useful Extras

                    ```bash
                    git log --oneline --graph --all
                    git bisect start            # binary search for a bug
                    git cherry-pick <sha>       # apply a specific commit
                    ```
                    MD,
                'responses' => [],
            ],
            [
                'type' => 'Snippet',
                'topics' => ['Programming', 'Laravel'],
                'title' => 'PHP: Useful Array and Collection Tricks',
                'content' => <<<'MD'
                    # PHP: Useful Array & Collection Tricks

                    ## Native Array Functions

                    ```php
                    // Filter
                    $evens = array_filter($nums, fn ($n) => $n % 2 === 0);

                    // Transform
                    $doubled = array_map(fn ($n) => $n * 2, $nums);

                    // Reduce to single value
                    $sum = array_reduce($nums, fn ($carry, $n) => $carry + $n, 0);

                    // Sort preserving keys
                    uasort($arr, fn ($a, $b) => $a['name'] <=> $b['name']);

                    // Flatten one level
                    $flat = array_merge(...$nested);

                    // Unique values
                    $unique = array_values(array_unique($arr));

                    // Key lookup
                    $key = array_search('needle', $haystack);

                    // Chunk for batching
                    $batches = array_chunk($items, 100);
                    ```

                    ## Laravel Collections

                    ```php
                    collect($items)
                        ->filter(fn ($i) => $i->active)
                        ->sortBy('name')
                        ->groupBy('category')
                        ->map(fn ($group) => $group->count())
                        ->toArray();

                    // Pluck a field
                    $names = collect($users)->pluck('name');

                    // First match
                    $admin = collect($users)->first(fn ($u) => $u->isAdmin());

                    // Flat map (map + flatten)
                    $tags = collect($posts)->flatMap(fn ($p) => $p->tags);

                    // Key by field
                    $byId = collect($users)->keyBy('id');
                    ```
                    MD,
                'responses' => [],
            ],

            // ---------------------------------------------------------------
            // Articles
            // ---------------------------------------------------------------
            [
                'type' => 'Article',
                'topics' => ['AI & Machine Learning', 'Productivity'],
                'title' => 'What Is Retrieval-Augmented Generation (RAG)?',
                'content' => <<<'MD'
                    # What Is Retrieval-Augmented Generation (RAG)?

                    **RAG** is a technique that enhances AI responses by grounding them in a personal knowledge base rather than relying solely on the model's training data.

                    ## How It Works

                    1. You store knowledge as structured entries (this app)
                    2. When you ask a question, relevant entries are retrieved
                    3. Those entries are injected into the AI prompt as context
                    4. The AI answers using both its training and your specific knowledge

                    ## Why It Matters

                    | Without RAG | With RAG |
                    |-------------|----------|
                    | Generic answers | Answers based on your data |
                    | Knowledge cutoff | Always up to date |
                    | Hallucinations on specifics | Grounded in real sources |
                    | Forgets previous conversations | Persistent knowledge base |

                    ## Best Practices

                    - Write entries in clear, factual prose
                    - Prefer shorter, focused entries over long documents
                    - Update entries when information changes
                    - Use consistent terminology across entries
                    - Tag entries with topics so the LLM can filter by category

                    ## MCP vs Classic RAG

                    Classic RAG uses vector embeddings and semantic search. This app uses the **Model Context Protocol** instead: the LLM actively queries tools to retrieve knowledge, which gives it more control and flexibility without needing a vector database.
                    MD,
                'responses' => [
                    [
                        'content' => <<<'MD'
                            ## Additional Notes

                            The key insight of RAG is separating **knowledge storage** from **knowledge retrieval**. The LLM does not need to be retrained when your knowledge base changes — it simply queries the latest data at inference time.

                            **MCP-based RAG** (as used in this app) differs from classic embedding-based RAG:

                            - **Embedding RAG:** chunks documents into vectors, finds nearest neighbours by cosine similarity — great for semantic search at scale
                            - **MCP RAG:** the LLM calls structured tools to search and retrieve — more controllable, no vector DB required, easier to debug

                            For a personal knowledge base with hundreds to low thousands of entries, MCP-based retrieval is simpler and more transparent.
                            MD,
                    ],
                ],
            ],

            // ---------------------------------------------------------------
            // Questions (with answers as responses)
            // ---------------------------------------------------------------
            [
                'type' => 'Question',
                'topics' => ['AI & Machine Learning'],
                'title' => 'What is the difference between RAG and fine-tuning an LLM?',
                'content' => <<<'MD'
                    When should I use RAG versus fine-tuning? What are the trade-offs in terms of cost, freshness, and accuracy?
                    MD,
                'responses' => [
                    [
                        'content' => <<<'MD'
                            ## RAG vs Fine-Tuning

                            These are complementary techniques, not alternatives.

                            ### Fine-Tuning

                            Fine-tuning updates a model's weights using additional training data, teaching it new **skills, styles, or domain-specific language**.

                            **Use fine-tuning when:**
                            - You need the model to follow a specific output format consistently
                            - You want to instil domain vocabulary or writing style
                            - You have thousands of high-quality labelled examples

                            **Limitations:**
                            - Expensive and slow to retrain
                            - Knowledge becomes stale as soon as training ends
                            - Prone to catastrophic forgetting (may degrade unrelated capabilities)
                            - Doesn't help with private or frequently changing data

                            ### RAG

                            RAG retrieves relevant documents at inference time and injects them into the prompt. The model's weights don't change.

                            **Use RAG when:**
                            - Your knowledge changes frequently
                            - You have private data you don't want to send to a training pipeline
                            - You need source attribution ("I found this in entry X")
                            - You want control over what context the model sees

                            **Limitations:**
                            - Retrieval quality determines answer quality — garbage in, garbage out
                            - Limited by context window size
                            - Adds latency for retrieval step

                            ### Summary Table

                            | | Fine-Tuning | RAG |
                            |-|-------------|-----|
                            | Knowledge freshness | Static (training time) | Dynamic (retrieval time) |
                            | Cost to update | High (retrain) | Low (add/edit entries) |
                            | Private data | Risky | Safe (stays on your server) |
                            | Best for | Skills & style | Facts & knowledge |

                            **In practice:** use RAG for your knowledge base and fine-tuning (if at all) for behaviour and output format.
                            MD,
                    ],
                ],
            ],
            [
                'type' => 'Question',
                'topics' => ['Laravel', 'Programming'],
                'title' => 'How do I set up Laravel Passport for an OAuth2 API?',
                'content' => <<<'MD'
                    What are the steps to add Laravel Passport to a new Laravel project so it can issue OAuth2 tokens for an API?
                    MD,
                'responses' => [
                    [
                        'content' => <<<'MD'
                            ## Setting Up Laravel Passport

                            ### 1. Install

                            ```bash
                            composer require laravel/passport
                            php artisan migrate          # creates OAuth tables
                            php artisan passport:keys    # generates encryption keys
                            ```

                            ### 2. Configure the User model

                            ```php
                            use Laravel\Passport\HasApiTokens;

                            class User extends Authenticatable
                            {
                                use HasApiTokens, HasFactory, Notifiable;
                            }
                            ```

                            ### 3. Set the API guard driver

                            In `config/auth.php`:

                            ```php
                            'guards' => [
                                'api' => [
                                    'driver'   => 'passport',
                                    'provider' => 'users',
                                ],
                            ],
                            ```

                            ### 4. Protect routes

                            ```php
                            Route::middleware('auth:api')->group(function () {
                                Route::get('/user', fn (Request $r) => $r->user());
                            });
                            ```

                            ### 5. Create a client and issue tokens

                            ```bash
                            # Personal access client (for testing)
                            php artisan passport:client --personal

                            # Or a regular client credential client
                            php artisan passport:client --client
                            ```

                            Then issue a token:

                            ```php
                            $token = $user->createToken('token-name')->accessToken;
                            ```

                            ### 6. Authenticate requests

                            ```
                            Authorization: Bearer <token>
                            ```

                            ### Key Tips

                            - Run `php artisan passport:keys --force` if you regenerate keys (invalidates existing tokens)
                            - Store `storage/oauth-*.key` securely — never commit them
                            - For MCP specifically, `Mcp::oauthRoutes()` in `routes/ai.php` handles the discovery and registration endpoints automatically
                            MD,
                    ],
                ],
            ],
            [
                'type' => 'Question',
                'topics' => ['DevOps', 'Programming'],
                'title' => 'How do I set up a cron job to run Laravel scheduled tasks?',
                'content' => <<<'MD'
                    How do I configure a server cron job so Laravel's task scheduler runs automatically every minute?
                    MD,
                'responses' => [
                    [
                        'content' => <<<'MD'
                            ## Laravel Scheduler Cron Setup

                            Laravel's scheduler requires a single cron entry that runs every minute. The scheduler itself then decides which tasks are due.

                            ### 1. Add the cron entry

                            Open the crontab for the web server user:

                            ```bash
                            crontab -e -u www-data    # or forge, ubuntu, etc.
                            ```

                            Add this line:

                            ```
                            * * * * * cd /path/to/your/app && php artisan schedule:run >> /dev/null 2>&1
                            ```

                            Replace `/path/to/your/app` with the absolute path to your Laravel project root.

                            ### 2. Define scheduled tasks

                            In `routes/console.php` (Laravel 11+):

                            ```php
                            use Illuminate\Support\Facades\Schedule;

                            Schedule::command('inspire')->hourly();
                            Schedule::job(new ProcessPodcast)->dailyAt('12:00');
                            Schedule::call(fn () => DB::table('recent_users')->delete())->daily();
                            ```

                            ### 3. Verify it's running

                            ```bash
                            # Test manually
                            php artisan schedule:run

                            # List all scheduled tasks
                            php artisan schedule:list

                            # Run the scheduler in the foreground (dev only)
                            php artisan schedule:work
                            ```

                            ### On Laravel Forge

                            Forge manages this automatically. Go to **Server → Scheduler** and add a job:

                            - **Command:** `php artisan schedule:run`
                            - **Frequency:** Every Minute
                            - **User:** forge

                            Forge writes the cron entry for you.
                            MD,
                    ],
                ],
            ],
            [
                'type' => 'Question',
                'topics' => ['AI & Machine Learning', 'Productivity'],
                'title' => 'What prompts work well for getting the AI to search my knowledge base?',
                'content' => <<<'MD'
                    What are some effective ways to prompt my AI assistant so it actually uses the MCP tools to search my knowledge base instead of just answering from memory?
                    MD,
                'responses' => [],
            ],
        ];

        foreach ($definitions as $definition) {
            $entry = Entry::create([
                'user_id' => $user->id,
                'type_id' => $types[$definition['type']]->id,
                'title' => $definition['title'],
                'content' => $definition['content'],
            ]);

            $topicIds = array_map(
                fn (string $name) => $topics[$name]->id,
                $definition['topics'],
            );

            $entry->topics()->sync($topicIds);

            foreach ($definition['responses'] as $response) {
                ResponseModel::create([
                    'entry_id' => $entry->id,
                    'user_id' => $user->id,
                    'content' => $response['content'],
                    'mime_type' => 'text/markdown',
                ]);
            }
        }
    }
}
