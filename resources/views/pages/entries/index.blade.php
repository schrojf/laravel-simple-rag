@extends('layouts.app')
@section('title', 'Entries')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900">Entries</h1>
        <p class="text-sm text-zinc-500 mt-1">Your knowledge base entries.</p>
    </div>
    <a href="{{ route('entries.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
        New Entry
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('entries.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search entries…"
        class="border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-56"
    >

    <select name="type_id" class="border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <option value="">All types</option>
        @foreach($entryTypes as $entryType)
            <option value="{{ $entryType->id }}" @selected(request('type_id') == $entryType->id)>
                {{ $entryType->name }}
            </option>
        @endforeach
    </select>

    <select name="topic_id" class="border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <option value="">All topics</option>
        @foreach($topics as $topic)
            <option value="{{ $topic->id }}" @selected(request('topic_id') == $topic->id)>
                {{ $topic->name }}
            </option>
        @endforeach
    </select>

    <button type="submit"
            class="bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
        Filter
    </button>

    @if(request()->hasAny(['search', 'type_id', 'topic_id']))
        <a href="{{ route('entries.index') }}"
           class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">
            Clear
        </a>
    @endif
</form>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($entries->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No entries found.</p>
            <a href="{{ route('entries.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Create your first entry
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50">
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Topics</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Tokens</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Updated</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($entries as $entry)
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('entries.show', $entry) }}" class="font-medium text-zinc-900 hover:text-indigo-600 transition-colors">
                                {{ $entry->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            @if($entry->type)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                      @if($entry->type->color) style="background-color: {{ $entry->type->color }}20; color: {{ $entry->type->color }}" @else class="bg-indigo-100 text-indigo-700" @endif>
                                    {{ $entry->type->name }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($entry->topics as $topic)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600">
                                        {{ $topic->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-zinc-400 text-xs">~{{ number_format($entry->token_estimate) }}</td>
                        <td class="px-4 py-3 text-zinc-400">{{ $entry->updated_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('entries.edit', $entry) }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('entries.destroy', $entry) }}"
                                      onsubmit="return confirm('Delete entry \"{{ addslashes($entry->title) }}\"?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-600 hover:text-red-700 font-medium cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($entries->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $entries->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
