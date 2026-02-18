@extends('layouts.app')
@section('title', 'Password')

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
       class="pb-2.5 text-sm font-medium border-b-2 border-indigo-600 -mb-px text-zinc-900">
        Password
    </a>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Change password</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Ensure your account uses a strong password.</p>
    </div>

    <div class="px-6 py-5">
        @if(session('status') === 'password-updated')
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                Password updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-zinc-700 mb-1.5">Current password</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required
                    autofocus
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full border @error('current_password', 'updatePassword') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                @error('current_password', 'updatePassword')
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
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full border @error('password', 'updatePassword') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                @error('password', 'updatePassword')
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
                    class="w-full border @error('password_confirmation', 'updatePassword') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                @error('password_confirmation', 'updatePassword')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Update password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
