@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}"
       class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
        ← Users
    </a>
</div>

<div class="mb-6 flex items-center gap-4">
    <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-semibold uppercase">
        {{ $user->initials() }}
    </div>
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900">{{ $user->name }}</h1>
        <p class="text-sm text-zinc-500">{{ $user->email }}</p>
    </div>
</div>

<div class="space-y-4">
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm divide-y divide-zinc-100">
        <div class="px-5 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-500">Name</span>
            <span class="text-sm text-zinc-900">{{ $user->name }}</span>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-500">Email</span>
            <span class="text-sm text-zinc-900">{{ $user->email }}</span>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-500">Role</span>
            <span>
                @if($user->isAdmin())
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Admin</span>
                @else
                    <span class="text-sm text-zinc-400">User</span>
                @endif
            </span>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-500">Email Verified</span>
            <span class="text-sm text-zinc-900">
                @if($user->email_verified_at)
                    <span class="text-green-600">✓ {{ $user->email_verified_at->format('Y-m-d') }}</span>
                @else
                    <span class="text-zinc-400">Not verified</span>
                @endif
            </span>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-500">Joined</span>
            <span class="text-sm text-zinc-900">{{ $user->created_at->format('Y-m-d') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
        <div class="px-5 py-4 border-b border-zinc-100">
            <h2 class="text-sm font-semibold text-zinc-700">Invitation Code</h2>
        </div>
        <div class="px-5 py-4">
            @if($user->usedInvitationCode)
                <p class="font-mono text-sm font-medium text-zinc-900">{{ $user->usedInvitationCode->code }}</p>
                <p class="text-xs text-zinc-400 mt-1">Used {{ $user->usedInvitationCode->used_at?->format('Y-m-d H:i') }}</p>
            @else
                <p class="text-sm text-zinc-400">No invitation code — open registration was used.</p>
            @endif
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-5 py-4">
        <p class="text-xs text-zinc-500">
            Admin privileges can only be changed via the Artisan console:
            <code class="font-mono bg-zinc-100 px-1 py-0.5 rounded text-zinc-700">php artisan user:manage promote --email={{ $user->email }}</code>
        </p>
    </div>
</div>
@endsection
