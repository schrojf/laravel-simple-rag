@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-zinc-900">Users</h1>
    <p class="text-sm text-zinc-500 mt-1">All registered accounts.</p>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
    @if($users->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-sm text-zinc-500">No users found.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50">
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">User</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Role</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Email Verified</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 uppercase tracking-wider text-xs">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($users as $user)
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-semibold uppercase">
                                    {{ $user->initials() }}
                                </div>
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $user->name }}</p>
                                    <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Admin</span>
                            @else
                                <span class="text-zinc-400 text-xs">User</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-500">
                            @if($user->email_verified_at)
                                <span class="text-green-600">✓</span>
                            @else
                                <span class="text-zinc-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-400">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-zinc-100">
                {{ $users->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
