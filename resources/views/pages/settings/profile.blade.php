@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-zinc-900">Settings</h1>
</div>

<!-- Settings sub-nav -->
<div class="flex border-b border-zinc-200 mb-8 gap-4">
    <a href="{{ route('settings.profile') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-indigo-600 -mb-px text-zinc-900">
        Profile
    </a>
    <a href="{{ route('settings.password') }}"
       class="pb-2.5 text-sm font-medium border-b-2 border-transparent -mb-px text-zinc-500 hover:text-zinc-700 transition-colors">
        Password
    </a>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Profile information</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Update your name and email address.</p>
    </div>

    <div class="px-6 py-5">
        @if(session('status') === 'profile-information-updated')
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                Profile updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('user-profile-information.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Full name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full border @error('name', 'updateProfileInformation') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
                >
                @error('name', 'updateProfileInformation')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                    autocomplete="email"
                    class="w-full border @error('email', 'updateProfileInformation') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
                >
                @error('email', 'updateProfileInformation')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
