@extends('layouts.app')
@section('title', 'New Invitation Code')

@section('content')
<div id="adminInvitationCodesPage">
<div class="mb-6">
    <a href="{{ route('admin.invitation-codes.index') }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Invitation Codes</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">New Invitation Code</h1>
</div>

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Code details</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Leave the code field blank to auto-generate one.</p>
    </div>

    <div class="px-6 py-5">
        <form method="POST" action="{{ route('admin.invitation-codes.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-zinc-700 mb-1.5">Code</label>
                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code') }}"
                    maxlength="11"
                    placeholder="Leave blank to auto-generate"
                    style="text-transform: uppercase"
                    autocomplete="off"
                    class="w-full border @error('code') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 font-mono focus:ring-2 focus:ring-indigo-500"
                >
                <p class="mt-1.5 text-xs text-zinc-400">Format: ABC-DEF-GHJ (alphanumeric, 3 segments of 3 characters)</p>
                @error('code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-zinc-700 mb-1.5">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Optional note about this code's purpose"
                    class="w-full border @error('description') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 resize-none"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2.5">
                <input
                    type="checkbox"
                    id="active"
                    name="active"
                    value="1"
                    {{ old('active', '1') ? 'checked' : '' }}
                    class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                >
                <label for="active" class="text-sm font-medium text-zinc-700 cursor-pointer">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Create
                </button>
                <a href="{{ route('admin.invitation-codes.index') }}"
                   class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>{{-- #adminInvitationCodesPage --}}
@endsection
