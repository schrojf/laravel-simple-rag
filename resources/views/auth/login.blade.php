@extends('layouts.auth')
@section('title', 'Sign in')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Sign in to your account</h1>
        <p class="text-sm text-zinc-500 mt-1">Welcome back. Enter your credentials to continue.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full border @error('email') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500"
            >
            <label for="remember" class="text-sm text-zinc-600">Remember me</label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Sign in
        </button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-sm text-zinc-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Create one</a>
        </p>
    @endif
</div>
@endsection
