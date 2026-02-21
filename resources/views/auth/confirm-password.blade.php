@extends('layouts.auth')
@section('title', 'Confirm password')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Confirm your password</h1>
        <p class="text-sm text-zinc-500 mt-1">Please confirm your password before continuing to this secure area.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-zinc-700 mb-1.5">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autofocus
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
            >
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Confirm
        </button>
    </form>
</div>
@endsection
