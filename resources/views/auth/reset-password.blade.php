@extends('layouts.auth')
@section('title', 'Set new password')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Set new password</h1>
        <p class="text-sm text-zinc-500 mt-1">Choose a strong password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ $request->email }}"
                required
                readonly
                autocomplete="email"
                class="w-full border border-zinc-200 rounded-lg px-3 py-2 text-sm text-zinc-500 bg-zinc-50 cursor-not-allowed"
            >
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-zinc-700 mb-1.5">New password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autofocus
                autocomplete="new-password"
                placeholder="••••••••"
                class="w-full border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1.5">Confirm new password</label>
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
            Reset password
        </button>
    </form>
</div>
@endsection
