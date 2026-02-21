@extends('layouts.app')
@section('title', 'Edit Entry')

@section('content')
<div id="entriesEditorPage">
<div class="mb-6">
    <a href="{{ route('entries.show', $entry) }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; {{ $entry->title }}</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">Edit Entry</h1>
</div>

<form method="POST" action="{{ route('entries.update', $entry) }}" class="space-y-5">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-100">
            <h2 class="text-base font-medium text-zinc-900">Details</h2>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-zinc-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $entry->title) }}"
                    autocomplete="off"
                    class="w-full border @error('title') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
                >
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type_id" class="block text-sm font-medium text-zinc-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                <select
                    id="type_id"
                    name="type_id"
                    class="w-full border @error('type_id') border-red-500 @else border-zinc-300 @enderror rounded-lg pl-3 pr-10 py-2 text-sm text-zinc-900 focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Select a type…</option>
                    @foreach($entryTypes as $entryType)
                        <option value="{{ $entryType->id }}" @selected(old('type_id', $entry->type_id) == $entryType->id)>
                            {{ $entryType->name }}
                        </option>
                    @endforeach
                </select>
                @error('type_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($topics->isNotEmpty())
            <div>
                @php $selectedTopicIds = old('topics', $entry->topics->pluck('id')->toArray()); @endphp
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Topics</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($topics as $topic)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="topics[]"
                                value="{{ $topic->id }}"
                                @checked(in_array($topic->id, $selectedTopicIds))
                                class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                            >
                            <span class="text-sm text-zinc-700">{{ $topic->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('topics')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>
    </div>

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
                    class="flex-1 w-full px-4 py-3 text-sm text-zinc-900 font-mono resize-none border-0 shadow-none focus:outline-none focus:ring-0 @error('content') border border-red-500 rounded-b-xl @endif"
                >{{ old('content', $entry->content) }}</textarea>
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

    <div class="flex items-center gap-3">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Save changes
        </button>
        <a href="{{ route('entries.show', $entry) }}"
           class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
            Cancel
        </a>
    </div>
</form>

<div class="mt-6 bg-white rounded-xl border border-red-200 shadow-sm">
    <div class="px-6 py-5 border-b border-red-100">
        <h2 class="text-base font-medium text-red-700">Danger zone</h2>
    </div>
    <div class="px-6 py-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-zinc-900">Delete this entry</p>
            <p class="text-sm text-zinc-500 mt-0.5">This action cannot be undone.</p>
        </div>
        <form method="POST" action="{{ route('entries.destroy', $entry) }}"
              data-confirm="Delete entry &quot;{{ $entry->title }}&quot;?">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Delete
            </button>
        </form>
    </div>
</div>
</div>{{-- #entriesEditorPage --}}
@endsection
