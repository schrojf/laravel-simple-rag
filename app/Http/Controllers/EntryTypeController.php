<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntryTypeRequest;
use App\Http\Requests\UpdateEntryTypeRequest;
use App\Models\EntryType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EntryTypeController extends Controller
{
    public function index(): View
    {
        $entryTypes = EntryType::query()
            ->where('user_id', Auth::id())
            ->withCount('entries')
            ->latest()
            ->paginate(20);

        return view('pages.entry-types.index', compact('entryTypes'));
    }

    public function create(): View
    {
        return view('pages.entry-types.create');
    }

    public function store(StoreEntryTypeRequest $request): RedirectResponse
    {
        EntryType::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'icon' => $request->input('icon'),
        ]);

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$request->input('name')}\" created successfully.");
    }

    public function edit(EntryType $entryType): View
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        return view('pages.entry-types.edit', compact('entryType'));
    }

    public function update(UpdateEntryTypeRequest $request, EntryType $entryType): RedirectResponse
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        $entryType->update([
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'icon' => $request->input('icon'),
        ]);

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$entryType->name}\" updated successfully.");
    }

    public function destroy(EntryType $entryType): RedirectResponse
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        $name = $entryType->name;
        $entryType->delete();

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$name}\" deleted successfully.");
    }
}
