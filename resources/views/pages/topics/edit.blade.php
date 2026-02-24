@extends('layouts.app')
@section('title', 'Edit Topic')

@section('content')
<div class="mb-6">
    <a href="{{ route('topics.index') }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Topics</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">Edit: {{ $topic->name }}</h1>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg mb-6">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Topic details</h2>
    </div>

    <div class="px-6 py-5">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('topics.update', $topic) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $topic->name) }}"
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
                    value="{{ old('color', $topic->color) }}"
                    placeholder="#6366f1"
                    autocomplete="off"
                    class="w-full border @error('color') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 font-mono focus:ring-2 focus:ring-indigo-500"
                >
                @error('color')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Icon</label>
                @php $selectedIcon = old('icon', $topic->icon ?? ''); @endphp
                <div class="flex flex-wrap gap-2">
                    <label class="flex flex-col items-center gap-1 px-2 py-2 rounded-lg border cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 border-zinc-200 hover:border-zinc-300 transition-colors">
                        <input type="radio" name="icon" value="" class="sr-only" @checked($selectedIcon === '')>
                        <span class="w-5 h-5 flex items-center justify-center text-zinc-300 text-base leading-none">∅</span>
                        <span class="text-xs text-zinc-400">none</span>
                    </label>
                    @foreach($icons as $name => $data)
                    <label class="flex flex-col items-center gap-1 px-2 py-2 rounded-lg border cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 border-zinc-200 hover:border-zinc-300 transition-colors">
                        <input type="radio" name="icon" value="{{ $name }}" class="sr-only" @checked($selectedIcon === $name)>
                        @icon($name, ['class' => 'w-5 h-5 text-zinc-600'])
                        <span class="text-xs text-zinc-500">{{ Str::headline($name) }}</span>
                    </label>
                    @endforeach
                </div>
                @error('icon')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Save changes
                </button>
                <a href="{{ route('topics.index') }}"
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
    <div class="px-6 py-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-zinc-900">Delete this topic</p>
            <p class="text-sm text-zinc-500 mt-0.5">Entries assigned to this topic will not be deleted.</p>
        </div>
        <form method="POST" action="{{ route('topics.destroy', $topic) }}"
              data-confirm="Delete topic &quot;{{ $topic->name }}&quot;?">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
