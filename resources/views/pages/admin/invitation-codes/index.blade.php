@extends('layouts.app')
@section('title', 'Invitation Codes')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900">Invitation Codes</h1>
        <p class="text-sm text-zinc-500 mt-1">Manage codes that grant access to registration.</p>
    </div>
    <a href="{{ route('admin.invitation-codes.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
        New Code
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($invitationCodes->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No invitation codes yet.</p>
            <a href="{{ route('admin.invitation-codes.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Create your first code
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50">
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Active</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Used At</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Used By</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Description</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Created</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($invitationCodes as $code)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-zinc-900 font-medium">{{ $code->code }}</td>
                            <td class="px-4 py-3">
                                @if($code->active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-500">Inactive</span>
                                @endif
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3 text-zinc-500">{{ $code->used_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="hidden sm:table-cell px-4 py-3">
                                @if($code->usedBy)
                                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">{{ $code->usedBy->name }}</a>
                                    <span class="block text-xs text-zinc-400">{{ $code->usedBy->email }}</span>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3 text-zinc-500 max-w-xs truncate">{{ $code->description ?? '—' }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 text-zinc-400">{{ $code->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.invitation-codes.edit', $code) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.invitation-codes.destroy', $code) }}"
                                          class="hidden sm:block"
                                          data-confirm="Delete invitation code {{ $code->code }}?">
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
        </div>

        @if($invitationCodes->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $invitationCodes->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
