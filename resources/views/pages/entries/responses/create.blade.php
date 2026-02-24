@extends('layouts.app')
@section('title', 'New Response')

@section('content')
<div id="entriesEditorPage">
<div class="mb-6">
    <a href="{{ route('entries.show', $entry) }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; {{ $entry->title }}</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">New Response</h1>
</div>

<form method="POST" action="{{ route('entries.responses.store', $entry) }}" class="space-y-5">
    @csrf

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
            <h2 class="text-base font-medium text-zinc-900">Content</h2>
            <span id="tokenCount" class="text-xs text-zinc-400">0 tokens</span>
        </div>
        <div class="grid grid-cols-2 divide-x divide-zinc-200" style="min-height: 400px">
            <div class="flex flex-col">
                <div class="px-3 py-2 border-b border-zinc-100 bg-zinc-50">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wider">Markdown</span>
                </div>
                <textarea
                    id="content"
                    name="content"
                    placeholder="Write your response in Markdown…"
                    class="flex-1 w-full px-4 py-3 text-sm text-zinc-900 placeholder-zinc-400 font-mono resize-none border-0 shadow-none focus:outline-none focus:ring-0 @error('content') border border-red-500 rounded-b-xl @endif"
                >{{ old('content') }}</textarea>
            </div>
            <div class="flex flex-col">
                <div class="px-3 py-2 border-b border-zinc-100 bg-zinc-50">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wider">Preview</span>
                </div>
                <div id="markdownPreview" class="flex-1 px-4 py-3 text-sm text-zinc-700 prose prose-zinc prose-sm max-w-none overflow-auto">
                    <p class="text-zinc-400 italic">Preview will appear here…</p>
                </div>
            </div>
        </div>
        @error('content')
            <div class="px-6 py-2 border-t border-red-100">
                <p class="text-red-600 text-sm">{{ $message }}</p>
            </div>
        @enderror
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-100">
            <h2 class="text-base font-medium text-zinc-900">Meta</h2>
        </div>
        <div class="px-6 py-5">
            <label for="meta" class="block text-sm font-medium text-zinc-700 mb-1.5">Meta</label>
            <textarea
                id="meta"
                name="meta"
                rows="3"
                placeholder='{"model_name": "gpt-4", "source_url": "https://..."}'
                class="w-full border @error('meta') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 font-mono placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >{{ old('meta') }}</textarea>
            <p class="mt-1.5 text-xs text-zinc-400">Optional JSON object with arbitrary key-value pairs.</p>
            @error('meta')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Add Response
        </button>
        <a href="{{ route('entries.show', $entry) }}"
           class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
            Cancel
        </a>
    </div>
</form>
</div>{{-- #entriesEditorPage --}}
@endsection
