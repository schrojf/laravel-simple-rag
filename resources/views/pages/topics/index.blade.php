@extends('layouts.app')
@section('title', 'Topics')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900">Topics</h1>
        <p class="text-sm text-zinc-500 mt-1">Organize entries into topics (many-to-many tags).</p>
    </div>
    <a href="{{ route('topics.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
        New Topic
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($topics->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No topics yet.</p>
            <a href="{{ route('topics.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Create your first topic
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50">
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Name</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Color</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Icon</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Entries</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($topics as $topic)
                        <tr class="relative hover:bg-zinc-50 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('topics.edit', $topic) }}"
                                   class="font-medium text-zinc-900 after:absolute after:inset-0">
                                    {{ $topic->name }}
                                </a>
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3">
                                @if($topic->color)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block h-4 w-4 rounded border border-zinc-200" style="background-color: {{ $topic->color }}"></span>
                                        <span class="text-zinc-500 font-mono text-xs">{{ $topic->color }}</span>
                                    </div>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-500">{{ $topic->icon ?? '—' }}</td>
                            <td class="hidden sm:table-cell px-4 py-3">
                                @if($topic->entries_count > 0)
                                    <a href="{{ route('entries.index', ['topic_id' => $topic->id]) }}"
                                       class="relative text-zinc-600 font-medium hover:text-indigo-600 transition-colors">
                                        {{ number_format($topic->entries_count) }}
                                    </a>
                                @else
                                    <span class="text-zinc-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('topics.edit', $topic) }}"
                                   class="relative text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($topics->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $topics->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
