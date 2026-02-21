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
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      @if($entry->type->color) style="background-color: {{ $entry->type->color }}20; color: {{ $entry->type->color }}" @else class="bg-indigo-100 text-indigo-700" @endif>
                    {{ $entry->type->name }}
                </span>
            @endif
            @foreach($entry->topics as $topic)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600">
                    {{ $topic->name }}
                </span>
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
@endsection
