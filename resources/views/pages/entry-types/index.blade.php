@extends('layouts.app')
@section('title', 'Entry Types')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900">Entry Types</h1>
        <p class="text-sm text-zinc-500 mt-1">Define labels to classify your knowledge entries.</p>
    </div>
    <a href="{{ route('entry-types.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
        New Type
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($entryTypes->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No entry types yet.</p>
            <a href="{{ route('entry-types.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Create your first type
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50">
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Color</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Icon</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Entries</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($entryTypes as $entryType)
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-zinc-900">{{ $entryType->name }}</td>
                        <td class="px-4 py-3">
                            @if($entryType->color)
                                <div class="flex items-center gap-2">
                                    <span class="inline-block h-4 w-4 rounded border border-zinc-200" style="background-color: {{ $entryType->color }}"></span>
                                    <span class="text-zinc-500 font-mono text-xs">{{ $entryType->color }}</span>
                                </div>
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-500">{{ $entryType->icon ?? '—' }}</td>
                        <td class="px-4 py-3 text-zinc-500">{{ number_format($entryType->entries_count) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('entry-types.edit', $entryType) }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('entry-types.destroy', $entryType) }}"
                                      onsubmit="return confirm('Delete entry type \"{{ $entryType->name }}\"?')">
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

        @if($entryTypes->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $entryTypes->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
