<?php

namespace App\Actions;

use App\Models\Entry;
use App\Models\EntryType;
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
            ['name' => 'Note',      'color' => '#6366f1'],
            ['name' => 'Article',   'color' => '#0ea5e9'],
            ['name' => 'Reference', 'color' => '#10b981'],
            ['name' => 'Snippet',   'color' => '#f59e0b'],
        ];

        $types = [];

        foreach ($definitions as $definition) {
            $types[$definition['name']] = EntryType::create([
                'user_id' => $user->id,
                'name' => $definition['name'],
                'color' => $definition['color'],
            ]);
        }

        return $types;
    }

    /** @return array<string, Topic> */
    private function seedTopics(User $user): array
    {
        $names = ['General', 'Programming', 'AI & Machine Learning', 'Productivity'];

        $topics = [];

        foreach ($names as $name) {
            $topics[$name] = Topic::create([
                'user_id' => $user->id,
                'name' => $name,
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
                    - Research findings and documentation

                    ## Organisation

                    - **Entry Types** categorise what kind of content an entry is (e.g. Note, Article, Reference, Snippet)
                    - **Topics** group entries by subject matter so related knowledge stays together

                    ## Tips for Better Retrieval

                    - Keep entries focused on a single idea or topic
                    - Use descriptive titles — they help the AI find the right entry
                    - The token count shown on each entry tells you how much context it uses when queried
                    MD,
            ],
            [
                'type' => 'Reference',
                'topics' => ['Programming'],
                'title' => 'Markdown Cheat Sheet',
                'content' => <<<'MD'
                    # Markdown Cheat Sheet

                    All entries are written in **GitHub Flavored Markdown**.

                    ## Headings

                    ```
                    # H1
                    ## H2
                    ### H3
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

                    Block:

                    ```php
                    echo 'Hello, World!';
                    ```

                    ## Links

                    ```
                    [Link text](https://example.com)
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
                    MD,
            ],
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

                    ## Best Practices

                    - Write entries in clear, factual prose
                    - Prefer shorter, focused entries over long documents
                    - Update entries when information changes
                    - Use consistent terminology across entries
                    MD,
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
        }
    }
}
