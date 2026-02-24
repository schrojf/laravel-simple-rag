<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function create(Entry $entry): View
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        return view('pages.entries.responses.create', compact('entry'));
    }

    public function store(Request $request, Entry $entry): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $entry->responses()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Response added.');
    }

    public function edit(Entry $entry, Response $response): View
    {
        abort_unless($entry->user_id === Auth::id(), 403);
        abort_unless($response->entry_id === $entry->id, 404);

        return view('pages.entries.responses.edit', compact('entry', 'response'));
    }

    public function update(Request $request, Entry $entry, Response $response): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);
        abort_unless($response->entry_id === $entry->id, 404);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $response->update($validated);

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Response updated.');
    }

    public function destroy(Entry $entry, Response $response): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);
        abort_unless($response->entry_id === $entry->id, 404);

        $response->delete();

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Response deleted.');
    }
}
