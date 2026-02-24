<?php

namespace App\Http\Controllers;

use App\Models\EntryType;
use App\Support\Icons;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $constraints = $this->constraints();
        $icons = Icons::all();

        return view('pages.entry-types.create', compact('constraints', 'icons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', Rule::in(array_keys(Icons::options()))],
        ]);

        EntryType::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$validated['name']}\" created successfully.");
    }

    public function edit(EntryType $entryType): View
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        $entryCount = $entryType->entries()->count();
        $constraints = $this->constraints();
        $icons = Icons::all();

        return view('pages.entry-types.edit', compact('entryType', 'entryCount', 'constraints', 'icons'));
    }

    protected function constraints(): array
    {
        return ['name' => 100];
    }

    public function update(Request $request, EntryType $entryType): RedirectResponse
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', Rule::in(array_keys(Icons::options()))],
        ]);

        $entryType->update($validated);

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$entryType->name}\" updated successfully.");
    }

    public function destroy(EntryType $entryType): RedirectResponse
    {
        abort_unless($entryType->user_id === Auth::id(), 403);

        if ($entryType->entries()->exists()) {
            return redirect()->route('entry-types.edit', $entryType)
                ->with('error', "Cannot delete \"{$entryType->name}\" — it has entries assigned to it. Reassign or delete those entries first.");
        }

        $name = $entryType->name;
        $entryType->delete();

        return redirect()->route('entry-types.index')
            ->with('success', "Entry type \"{$name}\" deleted successfully.");
    }
}
