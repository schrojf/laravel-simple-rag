@extends('layouts.app')
@section('title', 'New Topic')

@section('content')
<div class="mb-6">
    <a href="{{ route('topics.index') }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Topics</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">New Topic</h1>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Topic details</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Topics are tags used to group entries (e.g. Personal, Programming).</p>
    </div>

    <div class="px-6 py-5">
        <form method="POST" action="{{ route('topics.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Programming, Personal, TV Shows"
                    autocomplete="off"
                    maxlength="{{ $constraints['name'] }}"
                    class="w-full border @error('name') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="color" class="block text-sm font-medium text-zinc-700 mb-1.5">Color</label>
                <input
                    type="text"
                    id="color"
                    name="color"
                    value="{{ old('color') }}"
                    placeholder="#6366f1"
                    autocomplete="off"
                    class="w-full border @error('color') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 font-mono focus:ring-2 focus:ring-indigo-500"
                >
                <p class="mt-1.5 text-xs text-zinc-400">Optional hex color for UI display.</p>
                @error('color')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-zinc-700 mb-1.5">Icon</label>
                <input
                    type="text"
                    id="icon"
                    name="icon"
                    value="{{ old('icon') }}"
                    placeholder="e.g. tag, folder, star"
                    autocomplete="off"
                    class="w-full border @error('icon') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500"
                >
                @error('icon')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Create
                </button>
                <a href="{{ route('topics.index') }}"
                   class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
