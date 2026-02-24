@extends('layouts.app')
@section('title', 'API Tokens')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-zinc-900">Settings</h1>
</div>

<!-- Settings sub-nav -->
<div class="flex border-b border-zinc-200 mb-8 gap-4">
    <a href="{{ route('settings.profile') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-transparent -mb-px text-zinc-500 hover:text-zinc-700 transition-colors">
        Profile
    </a>
    <a href="{{ route('settings.password') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-transparent -mb-px text-zinc-500 hover:text-zinc-700 transition-colors">
        Password
    </a>
    <a href="{{ route('two-factor.show') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-transparent -mb-px text-zinc-500 hover:text-zinc-700 transition-colors">
        Two-Factor
    </a>
    <a href="{{ route('settings.tokens') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-indigo-600 -mb-px text-zinc-900">
        API Tokens
    </a>
</div>

@if (!$hasPersonalAccessClient)
    <div class="bg-amber-50 border border-amber-200 rounded-xl shadow-sm max-w-lg mb-6 px-6 py-5">
        <p class="text-sm font-medium text-amber-800">Personal access client not configured.</p>
        <p class="text-sm text-amber-700 mt-1">
            Run the following command to set it up:
        </p>
        <code class="mt-2 block text-xs font-mono bg-amber-100 text-amber-900 rounded px-3 py-2">
            php artisan passport:client --personal --no-interaction
        </code>
    </div>
@endif

@if (session('new_token'))
    <div class="bg-green-50 border border-green-200 rounded-xl shadow-sm max-w-lg mb-6 px-6 py-5">
        <p class="text-sm font-medium text-green-800 mb-2">Token created — copy it now, it won't be shown again.</p>
        <div class="flex items-stretch border border-green-300 rounded-lg overflow-hidden">
            <input
                id="newTokenInput"
                type="text"
                readonly
                value="{{ session('new_token') }}"
                class="w-full px-3 py-2 text-sm font-mono bg-white text-zinc-900 outline-none"
            />
            <button
                id="copyNewTokenBtn"
                type="button"
                class="px-3 border-l border-green-300 bg-white hover:bg-zinc-50 text-zinc-500 transition-colors cursor-pointer"
                title="Copy to clipboard"
                onclick="
                    navigator.clipboard.writeText(document.getElementById('newTokenInput').value);
                    this.querySelector('#copyTokenIcon').classList.add('hidden');
                    this.querySelector('#copiedTokenIcon').classList.remove('hidden');
                    setTimeout(() => {
                        this.querySelector('#copyTokenIcon').classList.remove('hidden');
                        this.querySelector('#copiedTokenIcon').classList.add('hidden');
                    }, 2000);
                "
            >
                <svg id="copyTokenIcon" xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                </svg>
                <svg id="copiedTokenIcon" xmlns="http://www.w3.org/2000/svg" class="size-4 text-green-500 hidden" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
@endif

@if (session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl shadow-sm max-w-lg mb-6 px-6 py-5">
        <p class="text-sm text-green-800">{{ session('success') }}</p>
    </div>
@endif

{{-- Create token card --}}
<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg mb-6">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Create token</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Tokens provide direct API access for scripts and integrations.</p>
    </div>

    <div class="px-6 py-5">
        <form method="POST" action="{{ route('settings.tokens.store') }}" class="flex items-end gap-3">
            @csrf

            <div class="flex-1">
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Token name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. My Script"
                    autocomplete="off"
                    maxlength="100"
                    @disabled(!$hasPersonalAccessClient)
                    class="w-full border @error('name') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 disabled:bg-zinc-50 disabled:text-zinc-400 disabled:cursor-not-allowed"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                @disabled(!$hasPersonalAccessClient)
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer disabled:bg-zinc-300 disabled:cursor-not-allowed shrink-0"
            >
                Create
            </button>
        </form>
    </div>
</div>

{{-- Existing tokens --}}
@if ($tokens->isNotEmpty())
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100">
            <p class="text-sm font-medium text-zinc-900">Active tokens</p>
        </div>
        <ul class="divide-y divide-zinc-100">
            @foreach ($tokens as $token)
                <li class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $token->name }}</p>
                        <p class="text-xs text-zinc-400 mt-0.5">
                            Created {{ $token->created_at->diffForHumans() }}
                            @if ($token->last_used_at)
                                &middot; Last used {{ $token->last_used_at->diffForHumans() }}
                            @else
                                &middot; Never used
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('settings.tokens.destroy', $token->id) }}"
                          data-confirm="Revoke token &quot;{{ $token->name }}&quot;?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-600 hover:text-red-700 font-medium transition-colors cursor-pointer shrink-0">
                            Revoke
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
