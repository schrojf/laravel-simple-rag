@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div id="dashboardPage">
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-zinc-900">Dashboard</h1>
    <p class="text-sm text-zinc-500 mt-1">Welcome back, {{ auth()->user()->name }}.</p>
</div>

{{-- Welcome card --}}
<div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6 mb-6">
    <div class="flex items-start gap-4">
        <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-6 w-6">
                <path d="M24 38C24 38 10 33 10 16L10 12C10 12 16 10 24 14C32 10 38 12 38 12L38 16C38 33 24 38 24 38Z"
                      stroke="#4f46e5" stroke-width="2" stroke-linejoin="round" fill="#eef2ff"/>
                <line x1="24" y1="14" x2="24" y2="38" stroke="#4f46e5" stroke-width="1.5"/>
                <line x1="14" y1="19" x2="22" y2="18" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                <line x1="14" y1="23" x2="22" y2="22" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                <line x1="26" y1="18" x2="34" y2="19" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                <line x1="26" y1="22" x2="34" y2="23" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M39 8L40 5L41 8L44 9L41 10L40 13L39 10L36 9Z"
                      fill="#4f46e5" stroke="#4f46e5" stroke-width="0.5" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h2 class="text-base font-medium text-zinc-900 mb-1">{{ config('app.name') }}</h2>
            <p class="text-sm text-zinc-500">
                Your personal knowledge base for organizing context, documents, and snippets
                to provide to LLMs via the MCP server. Use this dashboard to manage your
                knowledge entries and monitor activity.
            </p>
        </div>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">Entries</p>
        <p class="text-3xl font-semibold text-zinc-900">{{ number_format($entryCount) }}</p>
        <p class="text-sm text-zinc-400 mt-1">Knowledge entries</p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">Responses</p>
        <p class="text-3xl font-semibold text-zinc-900">{{ number_format($responseCount) }}</p>
        <p class="text-sm text-zinc-400 mt-1">AI responses stored</p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">MCP Calls</p>
        <p class="text-3xl font-semibold text-zinc-900">{{ number_format($mcpLogCount) }}</p>
        <p class="text-sm text-zinc-400 mt-1">Tool &amp; prompt calls</p>
    </div>
</div>

{{-- Recent activity --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- Recent Entries --}}
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between">
            <p class="text-sm font-medium text-zinc-900">Recent Entries</p>
            <a href="{{ route('entries.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                View all →
            </a>
        </div>
        @if($recentEntries->isEmpty())
            <div class="px-5 py-8 text-center">
                <p class="text-sm text-zinc-500">No entries yet.</p>
                <a href="{{ route('entries.create') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Create your first entry
                </a>
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach($recentEntries as $entry)
                    <li class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-zinc-50 transition-colors">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($entry->type)
                                <span class="shrink-0 inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-xs font-medium"
                                      @if($entry->type->color)
                                          style="background-color: {{ $entry->type->color }}20; color: {{ $entry->type->color }}"
                                      @else
                                          style="background-color: #e4e4e7; color: #52525b"
                                      @endif>
                                    @if($entry->type->icon) @icon($entry->type->icon, ['class' => 'w-3 h-3 shrink-0']) @endif
                                    {{ $entry->type->name }}
                                </span>
                            @endif
                            <a href="{{ route('entries.show', $entry) }}"
                               class="text-sm text-zinc-900 hover:text-indigo-600 truncate transition-colors">
                                {{ $entry->title }}
                            </a>
                        </div>
                        <span class="text-xs text-zinc-400 whitespace-nowrap shrink-0">{{ $entry->updated_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Recent MCP Calls --}}
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between">
            <p class="text-sm font-medium text-zinc-900">Recent MCP Calls</p>
            <a href="{{ route('mcp-logs.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                View all →
            </a>
        </div>
        @if($recentMcpLogs->isEmpty())
            <div class="px-5 py-8 text-center">
                <p class="text-sm text-zinc-500">No MCP calls yet.</p>
            </div>
        @else
            @php
                $mcpBadgeColors = [
                    'tool'     => 'bg-indigo-50 text-indigo-700',
                    'prompt'   => 'bg-amber-50 text-amber-700',
                    'resource' => 'bg-emerald-50 text-emerald-700',
                ];
            @endphp
            <ul class="divide-y divide-zinc-100">
                @foreach($recentMcpLogs as $log)
                    <li class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-zinc-50 transition-colors">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="shrink-0 inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium {{ $mcpBadgeColors[$log->primitive_type] ?? 'bg-zinc-100 text-zinc-600' }}">
                                {{ $log->primitive_type }}
                            </span>
                            <span class="text-sm text-zinc-700 truncate">{{ $log->primitive_name }}</span>
                        </div>
                        <span class="text-xs text-zinc-400 whitespace-nowrap shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
</div>{{-- #dashboardPage --}}
@endsection
