@extends('layouts.auth')
@section('title', 'Reset password')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Reset your password</h1>
        <p class="text-sm text-zinc-500 mt-1">Enter your email address and we'll send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Send reset link
        </button>
    </form>

    <p class="text-center text-sm text-zinc-500 mt-6">
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Back to sign in</a>
    </p>
</div>
@endsection
