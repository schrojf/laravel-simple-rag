# laravel-simple-rag — Feature Specification

> **Purpose of this document:** Authoritative feature specification for the `laravel-simple-rag` Laravel application. Intended as context for a Claude coding agent. All decisions marked ✅ are finalized. Items marked ⚠️ are open questions that require resolution before implementation.

---

## 1. Project Overview

**laravel-simple-rag** (Laravel Knowledge Provider) is a single-user, self-hosted Laravel application with two responsibilities:

1. **Web Knowledge Manager** — a CRUD web UI where the user organizes content (text snippets, documents, questions, context, etc.) into typed entries and topics.
2. **MCP Server** — exposes that content to LLMs via the Model Context Protocol, allowing LLMs to read and write back answers, scraped web data, and other generated content.

**Target user:** A single power user who self-hosts the app (e.g. via Laravel Forge) and manually registers the MCP server in their AI tooling (e.g. Claude Desktop).

---

## 2. Data Model

### 2.1 EntryType

User-defined labels that classify entries (e.g. `snippet`, `document`, `question`, `context`). Used as the primary filter when LLMs query entries.

| Field        | Type         | Notes                           |
| ------------ | ------------ | ------------------------------- |
| `id`         | bigint PK    |                                 |
| `user_id`    | FK → User    | Owner                           |
| `name`       | string       | e.g. `question`, `snippet`      |
| `color`      | string\|null | Optional hex color for UI       |
| `icon`       | string\|null | Optional icon identifier for UI |
| `created_at` | timestamp    |                                 |
| `updated_at` | timestamp    |                                 |

### 2.2 Topic

User-defined tags for organizing entries (many-to-many). Examples: `Personal`, `Programming`, `TV Shows`.

| Field        | Type         | Notes                           |
| ------------ | ------------ | ------------------------------- |
| `id`         | bigint PK    |                                 |
| `user_id`    | FK → User    | Owner                           |
| `name`       | string       |                                 |
| `color`      | string\|null | Optional hex color for UI       |
| `icon`       | string\|null | Optional icon identifier for UI |
| `created_at` | timestamp    |                                 |
| `updated_at` | timestamp    |                                 |

### 2.3 Entry

The primary knowledge unit. Stores markdown text with a user-defined type and attached topics.

| Field        | Type           | Notes                                                       |
| ------------ | -------------- | ----------------------------------------------------------- |
| `id`         | bigint PK      |                                                             |
| `user_id`    | FK → User      | Owner                                                       |
| `type_id`    | FK → EntryType | Required                                                    |
| `title`      | string         | Required                                                    |
| `content`    | longtext       | Markdown                                                    |
| `meta`       | json\|null     | Arbitrary key-value pairs (e.g. `source_url`, `model_name`) |
| `created_at` | timestamp      |                                                             |
| `updated_at` | timestamp      |                                                             |

**Pivot:** `entry_topic` (`entry_id`, `topic_id`)

**Eloquent relationships:**

- `Entry` belongs to `EntryType`
- `Entry` belongs to `User`
- `Entry` belongs to many `Topic` (via `entry_topic`)
- `Entry` has many `Response`

### 2.4 Response

A response attached to an `Entry`. Written by the user or by an LLM via MCP.

| Field        | Type            | Notes                                                                        |
| ------------ | --------------- | ---------------------------------------------------------------------------- |
| `id`         | bigint PK       |                                                                              |
| `entry_id`   | FK → Entry      | Parent entry                                                                 |
| `user_id`    | FK → User\|null | Null if written by an LLM                                                    |
| `content`    | longtext        | Markdown                                                                     |
| `mime_type`  | string          | Default `text/markdown`. Reserved for future file types (PDF, image, audio). |
| `meta`       | json\|null      | e.g. `model_name`, `source_url`                                              |
| `created_at` | timestamp       |                                                                              |
| `updated_at` | timestamp       |                                                                              |

**Eloquent relationships:**

- `Response` belongs to `Entry`
- `Response` belongs to `User` (nullable — null indicates LLM-authored)

> **Note:** Versioning / revision history is **out of scope for v1**.

### 2.5 Token Estimation

Every `Entry` and `Response` record should have its token count estimated and displayed in the UI. Estimation method: character-based approximation (`ceil(chars / 4)`) unless a tokenizer library is available.

A soft-limit (configurable per installation) determines the maximum content size fed to AI systems. Enforced at the application level, not the database level.

---

## 3. MCP Server

### 3.1 Authentication

All MCP clients (e.g. Claude Desktop) authenticate via **Laravel Passport OAuth2**. All MCP operations are scoped to the authenticated user. There is no guest or anonymous MCP access.

### 3.2 Rate Limiting

Basic rate limiting via Laravel's API rate-limit middleware. No advanced per-tool rate limiting in v1.

### 3.3 MCP Tools & Resources

All operations available in the web UI are also available via MCP. Destructive operations (delete entry, remove topic) are **web UI only** in v1.

| Tool / Prompt               | Direction | Description                                                                                                       |
| --------------------------- | --------- | ----------------------------------------------------------------------------------------------------------------- |
| `search_entries`            | LLM → App | Search entries with configurable filters: `type`, `topic`, `response_count`, keyword                              |
| `get_entry`                 | LLM → App | Fetch a single entry by ID; optionally include latest responses and response count                                |
| `get_responses`             | LLM → App | Fetch responses for an entry; configurable sort order and max limit                                               |
| `list_types`                | LLM → App | List all user-defined entry types                                                                                 |
| `list_topics`               | LLM → App | List all user-defined topics                                                                                      |
| `create_entry`              | LLM → App | Create a new entry for a given type                                                                               |
| `create_response`           | LLM → App | Create a new response attached to an entry                                                                        |
| `create_topic`              | LLM → App | Create a new topic if it does not yet exist                                                                       |
| `add_topic`                 | LLM → App | Attach an existing topic to an entry                                                                              |
| `answer_question` (prompt)  | App → LLM | MCP prompt instructing the LLM to find an unanswered question and store its answer as a response                  |
| `scrape_and_store` (prompt) | App → LLM | MCP prompt instructing the LLM to scrape a URL, optionally convert to Markdown, and store as an entry or response |

### 3.4 Key Use Cases

These use cases emerge from combining the tools above with user-defined prompts and filters.

#### Q&A Flow

1. User creates an `Entry` with type `question`.
2. LLM calls `search_entries` filtered by `type=question`, optionally filtering for entries with zero responses.
3. LLM calls `create_response` to store its answer linked to the entry.
4. User may review and edit the response in the web UI.

#### Web Scraping Flow

1. User provides a URL (via prompt or web UI).
2. LLM uses the `scrape_and_store` prompt: fetches URL, parses content, converts to Markdown, optionally translates.
3. Result is stored as a new `Entry` or as a `Response` on an existing entry.

#### Document / Snippet Management

- Markdown content is created and managed via MCP or web UI.
- Short snippets and long documents are both stored as `Entry` records; no structural difference in v1.

> ⚠️ **Open (post-v1):** Automatic document chunking for RAG vector search.

---

## 4. Web UI

### 4.1 Pages

| Page             | Route                                      | Description                                                             |
| ---------------- | ------------------------------------------ | ----------------------------------------------------------------------- |
| Dashboard        | `/`                                        | Overview: entry counts by type, recent activity                         |
| Entry List       | `/entries`                                 | Browse, filter (type, topic, response count), keyword search            |
| Entry Detail     | `/entries/{entry}`                         | View an entry and all its responses                                     |
| Entry Editor     | `/entries/create`, `/entries/{entry}/edit` | Create or edit an entry and its responses; Markdown editor with preview |
| Entry Types CRUD | `/types`                                   | Manage user-defined entry types (name, color, icon)                     |
| Topics CRUD      | `/topics`                                  | Manage user-defined topics (name, color, icon)                          |
| MCP Logs         | `/mcp-logs`                                | View MCP tool call history (tool name, input, response, timestamp)      |
| Settings         | `/settings`                                | App config, OAuth clients, soft-limit configuration                     |

### 4.2 Markdown Editor

Use [`markdown-it`](https://github.com/markdown-it/markdown-it) for rendering Markdown previews. The editor itself is a plain `<textarea>` with a live HTML preview panel.

### 4.3 Search

- **v1:** SQL `LIKE` search + MySQL/PostgreSQL `FULLTEXT` search on `title` and `content`.
- **Post-v1:** Meilisearch via Laravel Scout.
- **Future:** Vector/semantic search (pgvector, Qdrant, or Meilisearch vectors).

Filtering dimensions available in v1: `type_id`, `topic_id`, `response_count` (e.g. `= 0` for unanswered questions), keyword.

---

## 5. Tech Stack

| Layer         | Choice                      | Notes                                                         |
| ------------- | --------------------------- | ------------------------------------------------------------- |
| Backend       | Laravel (PHP)               | Latest stable                                                 |
| Database      | MySQL **or** PostgreSQL     | App must support both; use DB-agnostic Eloquent/query builder |
| Cache / Queue | Redis                       | Queues for async jobs (e.g. scraping)                         |
| Auth          | Laravel Passport            | OAuth2 for MCP clients                                        |
| Frontend      | Blade + Vanilla JS          | No JS framework in v1                                         |
| Markdown      | markdown-it                 | Client-side rendering                                         |
| Search        | MySQL / PostgreSQL FULLTEXT | Laravel Scout driver swap-ready for Meilisearch later         |
| Vector DB     | —                           | Out of scope for v1                                           |
| Deployment    | Laravel Forge               | Single-server self-hosted                                     |

---

## 6. Out of Scope — v1

The following features are explicitly deferred:

- Vector / semantic search and embeddings
- File uploads (PDF, TXT, DOCX, images, audio)
- PDF-to-Markdown conversion
- Public read-only knowledge base view
- Built-in chat / MCP test interface
- Multi-user support (roles, permissions, team/workspace isolation)
- Revision history / content versioning
- Per-tool MCP rate limiting

---

## 7. Open Questions

| #   | Question                                                                                                                               | Impact                             |
| --- | -------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------- |
| 1   | Token estimation: character-based approximation vs. integrating a PHP tokenizer?                                                       | UI display, soft-limit enforcement |
| 2   | MCP Logs: store full request/response payloads or only metadata?                                                                       | Storage size, privacy              |
| 3   | Should `create_entry` accept `topic` names directly (auto-creating if missing) or require explicit `create_topic` + `add_topic` calls? | MCP DX, tool count                 |

---

_Last updated: 2026-02-21_
