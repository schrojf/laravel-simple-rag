@extends('layouts.app')
@section('title', 'MCP Logs')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-zinc-900">MCP Logs</h1>
    <p class="text-sm text-zinc-500 mt-1">A record of every tool, prompt, and resource call made by AI clients.</p>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($logs->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No MCP requests logged yet.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50">
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Name</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Input</th>
                        <th class="hidden md:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Session</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($logs as $log)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-4 py-3">
                                @php
                                    $badgeColors = [
                                        'tool'     => 'bg-indigo-50 text-indigo-700',
                                        'prompt'   => 'bg-amber-50 text-amber-700',
                                        'resource' => 'bg-emerald-50 text-emerald-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $badgeColors[$log->primitive_type] ?? 'bg-zinc-100 text-zinc-600' }}">
                                    {{ $log->primitive_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-zinc-900">{{ $log->primitive_name }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 text-zinc-500 font-mono text-xs max-w-xs truncate">
                                @if($log->input)
                                    {{ \Illuminate\Support\Str::limit(json_encode($log->input), 120) }}
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-4 py-3 text-zinc-500 font-mono text-xs">
                                {{ $log->session_id ? substr($log->session_id, 0, 8) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $logs->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
