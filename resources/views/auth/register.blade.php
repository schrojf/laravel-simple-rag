@extends('layouts.auth')
@section('title', 'Create account')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Create your account</h1>
        <p class="text-sm text-zinc-500 mt-1">Start building your personal knowledge base.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        @if(config('app.require_invitation'))
        <div>
            <label for="invitation_code" class="block text-sm font-medium text-zinc-700 mb-1.5">Invitation Code</label>
            <input
                type="text"
                id="invitation_code"
                name="invitation_code"
                value="{{ old('invitation_code') }}"
                required
                autocomplete="off"
                maxlength="11"
                pattern="[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}"
                placeholder="ABC-DEF-GHJ"
                style="text-transform: uppercase"
                class="w-full border @error('invitation_code') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            <p class="mt-1.5 text-xs text-zinc-400">Enter the invitation code you received (format: XXX-XXX-XXX)</p>
            @error('invitation_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Full name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Jane Doe"
                class="w-full border @error('name') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full border @error('email') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-zinc-700 mb-1.5">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="w-full border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1.5">Confirm password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="w-full border @error('password_confirmation') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('password_confirmation')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Create account
        </button>
    </form>

    <p class="text-center text-sm text-zinc-500 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Sign in</a>
    </p>
</div>
@endsection
