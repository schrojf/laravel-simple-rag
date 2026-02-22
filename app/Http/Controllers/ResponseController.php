<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
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

    public function destroy(Entry $entry, Response $response): RedirectResponse
    {
        abort_unless($entry->user_id === Auth::id(), 403);
        abort_unless($response->entry_id === $entry->id, 404);

        $response->delete();

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Response deleted.');
    }
}
