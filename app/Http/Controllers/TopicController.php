<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Support\Icons;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    public function index(): View
    {
        $topics = Topic::query()
            ->where('user_id', Auth::id())
            ->withCount('entries')
            ->latest()
            ->paginate(20);

        return view('pages.topics.index', compact('topics'));
    }

    public function create(): View
    {
        $constraints = $this->constraints();
        $icons = Icons::all();

        return view('pages.topics.create', compact('constraints', 'icons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', Rule::in(array_keys(Icons::options()))],
        ]);

        Topic::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->route('topics.index')
            ->with('success', "Topic \"{$validated['name']}\" created successfully.");
    }

    public function edit(Topic $topic): View
    {
        abort_unless($topic->user_id === Auth::id(), 403);

        $constraints = $this->constraints();
        $icons = Icons::all();

        return view('pages.topics.edit', compact('topic', 'constraints', 'icons'));
    }

    protected function constraints(): array
    {
        return ['name' => 100];
    }

    public function update(Request $request, Topic $topic): RedirectResponse
    {
        abort_unless($topic->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', Rule::in(array_keys(Icons::options()))],
        ]);

        $topic->update($validated);

        return redirect()->route('topics.index')
            ->with('success', "Topic \"{$topic->name}\" updated successfully.");
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        abort_unless($topic->user_id === Auth::id(), 403);

        $name = $topic->name;
        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', "Topic \"{$name}\" deleted successfully.");
    }
}
