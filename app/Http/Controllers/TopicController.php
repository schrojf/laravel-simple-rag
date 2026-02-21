<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
        return view('pages.topics.create');
    }

    public function store(StoreTopicRequest $request): RedirectResponse
    {
        Topic::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'icon' => $request->input('icon'),
        ]);

        return redirect()->route('topics.index')
            ->with('success', "Topic \"{$request->input('name')}\" created successfully.");
    }

    public function edit(Topic $topic): View
    {
        abort_unless($topic->user_id === Auth::id(), 403);

        return view('pages.topics.edit', compact('topic'));
    }

    public function update(UpdateTopicRequest $request, Topic $topic): RedirectResponse
    {
        abort_unless($topic->user_id === Auth::id(), 403);

        $topic->update([
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'icon' => $request->input('icon'),
        ]);

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
