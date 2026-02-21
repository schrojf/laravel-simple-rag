<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntryRequest;
use App\Http\Requests\UpdateEntryRequest;
use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class EntryController extends Controller
{
    public function index(Request $request): View
    {
        $entryTypes = EntryType::query()->where('user_id', Auth::id())->orderBy('name')->get();
        $topics = Topic::query()->where('user_id', Auth::id())->orderBy('name')->get();

        $entries = Entry::query()
            ->where('user_id', Auth::id())
            ->with(['type', 'topics'])
            ->when($request->filled('type_id'), fn ($q) => $q->where('type_id', $request->integer('type_id')))
            ->when($request->filled('topic_id'), fn ($q) => $q->whereHas('topics', fn ($q) => $q->where('topics.id', $request->integer('topic_id'))))
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%')->orWhere('content', 'like', '%'.$request->string('search').'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.entries.index', compact('entries', 'entryTypes', 'topics'));
    }

    public function show(Entry $entry): View
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        $entry->load(['type', 'topics']);

        $renderedContent = (new GithubFlavoredMarkdownConverter(['html_input' => 'strip']))
            ->convert($entry->content)
            ->getContent();

        return view('pages.entries.show', compact('entry', 'renderedContent'));
    }

    public function create(): View
    {
        $entryTypes = EntryType::query()->where('user_id', Auth::id())->orderBy('name')->get();
        $topics = Topic::query()->where('user_id', Auth::id())->orderBy('name')->get();

        return view('pages.entries.create', compact('entryTypes', 'topics'));
    }

    public function store(StoreEntryRequest $request): RedirectResponse
    {
        $entry = Entry::create([
            'user_id' => Auth::id(),
            'type_id' => $request->integer('type_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        $entry->topics()->sync($request->input('topics', []));

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Entry created successfully.');
    }

    public function edit(Entry $entry): View
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        $entry->load('topics');
        $entryTypes = EntryType::query()->where('user_id', Auth::id())->orderBy('name')->get();
        $topics = Topic::query()->where('user_id', Auth::id())->orderBy('name')->get();

        return view('pages.entries.edit', compact('entry', 'entryTypes', 'topics'));
    }

    public function update(UpdateEntryRequest $request, Entry $entry): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        $entry->update([
            'type_id' => $request->integer('type_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        $entry->topics()->sync($request->input('topics', []));

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Entry updated successfully.');
    }

    public function destroy(Entry $entry): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        $entry->delete();

        return redirect()->route('entries.index')
            ->with('success', 'Entry deleted successfully.');
    }
}
