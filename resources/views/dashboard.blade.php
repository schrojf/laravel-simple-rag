@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div id="dashboardPage">
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-zinc-900">Dashboard</h1>
    <p class="text-sm text-zinc-500 mt-1">Welcome back, {{ auth()->user()->name }}.</p>
</div>

<!-- Welcome card -->
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

<!-- Placeholder stat cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">Documents</p>
        <p class="text-3xl font-semibold text-zinc-900">—</p>
        <p class="text-sm text-zinc-400 mt-1">Knowledge entries</p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">Snippets</p>
        <p class="text-3xl font-semibold text-zinc-900">—</p>
        <p class="text-sm text-zinc-400 mt-1">Context snippets</p>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-5">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-2">Queries</p>
        <p class="text-3xl font-semibold text-zinc-900">—</p>
        <p class="text-sm text-zinc-400 mt-1">LLM interactions</p>
    </div>
</div>
</div>{{-- #dashboardPage --}}
@endsection
