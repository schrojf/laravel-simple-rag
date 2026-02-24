@extends('layouts.app')
@section('title', 'Edit Entry Type')

@section('content')
<div class="mb-6">
    <a href="{{ route('entry-types.index') }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Entry Types</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">Edit: {{ $entryType->name }}</h1>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg mb-6">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Type details</h2>
    </div>

    <div class="px-6 py-5">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-lg px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('entry-types.update', $entryType) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $entryType->name) }}"
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
                    value="{{ old('color', $entryType->color) }}"
                    placeholder="#6366f1"
                    autocomplete="off"
                    class="w-full border @error('color') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 font-mono focus:ring-2 focus:ring-indigo-500"
                >
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
                    value="{{ old('icon', $entryType->icon) }}"
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
                    Save changes
                </button>
                <a href="{{ route('entry-types.index') }}"
                   class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-red-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-red-100">
        <h2 class="text-base font-medium text-red-700">Danger zone</h2>
    </div>
    <div class="px-6 py-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-zinc-900">Delete this type</p>
            @if($entryCount > 0)
                <p class="text-sm text-zinc-500 mt-0.5">
                    Cannot delete — {{ $entryCount }} {{ Str::plural('entry', $entryCount) }} assigned. Reassign or delete them first.
                </p>
            @else
                <p class="text-sm text-zinc-500 mt-0.5">No entries assigned. This type can be safely deleted.</p>
            @endif
        </div>
        @if($entryCount > 0)
            <span class="bg-zinc-100 text-zinc-400 font-medium text-sm py-2 px-4 rounded-lg cursor-not-allowed shrink-0">
                Delete
            </span>
        @else
            <form method="POST" action="{{ route('entry-types.destroy', $entryType) }}"
                  data-confirm="Delete entry type &quot;{{ $entryType->name }}&quot;?">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Delete
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
