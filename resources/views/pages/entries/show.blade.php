@extends('layouts.app')
@section('title', $entry->title)

@section('content')
<div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <a href="{{ route('entries.index') }}"
           class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Entries</a>
        <h1 class="text-2xl font-semibold text-zinc-900 mt-2">{{ $entry->title }}</h1>
        <div class="flex flex-wrap items-center gap-2 mt-2">
            @if($entry->type)
                <a href="{{ route('entries.index', ['type_id' => $entry->type->id]) }}"
                   class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium transition-opacity hover:opacity-75"
                   @if($entry->type->color) style="background-color: {{ $entry->type->color }}20; color: {{ $entry->type->color }}" @else class="bg-indigo-100 text-indigo-700" @endif>
                    @if($entry->type->icon) @icon($entry->type->icon, ['class' => 'w-3 h-3 shrink-0']) @endif
                    {{ $entry->type->name }}
                </a>
            @endif
            @foreach($entry->topics as $topic)
                <a href="{{ route('entries.index', ['topic_id' => $topic->id]) }}"
                   class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 hover:bg-zinc-200 transition-colors">
                    @if($topic->icon) @icon($topic->icon, ['class' => 'w-3 h-3 shrink-0']) @endif
                    {{ $topic->name }}
                </a>
            @endforeach
            <span class="text-xs text-zinc-400">~{{ number_format($entry->token_estimate) }} tokens</span>
        </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('entries.edit', $entry) }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Edit
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
    <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
        <h2 class="text-base font-medium text-zinc-900">Content</h2>
        <span class="text-xs text-zinc-400">Updated {{ $entry->updated_at->format('Y-m-d H:i') }}</span>
    </div>
    <div class="px-6 py-5 prose prose-zinc max-w-none">
        {!! $renderedContent !!}
    </div>
</div>

@if($entry->meta)
<div class="mt-6 bg-white rounded-xl border border-zinc-200 shadow-sm">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Meta</h2>
    </div>
    <dl class="px-6 py-2 divide-y divide-zinc-100">
        @foreach($entry->meta as $key => $value)
        <div class="py-2.5 flex gap-4 text-sm">
            <dt class="w-40 shrink-0 font-medium text-zinc-500">{{ $key }}</dt>
            <dd class="text-zinc-800 font-mono break-all">{{ is_array($value) ? json_encode($value) : $value }}</dd>
        </div>
        @endforeach
    </dl>
</div>
@endif

<div class="mt-6 bg-white rounded-xl border border-zinc-200 shadow-sm">
    <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
        <h2 class="text-base font-medium text-zinc-900">Responses ({{ $responses->total() }})</h2>
        <div class="flex items-center gap-3 text-sm">
            <a href="{{ route('entries.responses.create', $entry) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-1.5 px-3 rounded-lg transition-colors">
                Add Response
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
               class="{{ $sort === 'newest' ? 'text-indigo-600 font-medium' : 'text-zinc-500 hover:text-zinc-700 transition-colors' }}">
                Newest
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
               class="{{ $sort === 'oldest' ? 'text-indigo-600 font-medium' : 'text-zinc-500 hover:text-zinc-700 transition-colors' }}">
                Oldest
            </a>
        </div>
    </div>

    @forelse($responses as $response)
        <div class="px-6 py-5 border-b border-zinc-100 last:border-b-0">
            <div class="prose prose-zinc prose-sm max-w-none">
                {!! $response->rendered_content !!}
            </div>
            @if($response->meta)
            <dl class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                @foreach($response->meta as $key => $value)
                <div class="flex items-center gap-1 text-xs text-zinc-400">
                    <dt class="font-medium">{{ $key }}:</dt>
                    <dd class="font-mono">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                </div>
                @endforeach
            </dl>
            @endif
            <div class="mt-3 flex items-center justify-between">
                <span class="text-xs text-zinc-400">
                    @if(!$response->user_id)
                        AI &middot;
                    @elseif($response->user_id !== Auth::id())
                        {{ $response->user->name }} &middot;
                    @endif
                    {{ $response->created_at->format('Y-m-d H:i') }}
                </span>
                <div class="flex items-center gap-3">
                    <a href="{{ route('entries.responses.edit', [$entry, $response]) }}"
                       class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                        Edit
                    </a>
                    <form method="POST"
                          action="{{ route('entries.responses.destroy', [$entry, $response]) }}"
                          data-confirm="Delete this response?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 transition-colors cursor-pointer">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="px-6 py-8 text-center text-sm text-zinc-400">
            No responses yet.
        </div>
    @endforelse

    @if($responses->hasPages())
        <div class="px-6 py-4 border-t border-zinc-100">
            {{ $responses->links() }}
        </div>
    @endif
</div>

<div class="mt-6 bg-white rounded-xl border border-zinc-200 shadow-sm">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Add Response</h2>
    </div>
    <div class="px-6 py-5">
        <form method="POST" action="{{ route('entries.responses.store', $entry) }}">
            @csrf
            <div class="mb-4">
                <textarea name="content"
                          rows="3"
                          placeholder="Write your response in markdown..."
                          class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-mono text-zinc-900 placeholder-zinc-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none @error('content') border-red-400 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Add Response
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
