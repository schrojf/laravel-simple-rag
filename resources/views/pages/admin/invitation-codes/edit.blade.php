@extends('layouts.app')
@section('title', 'Edit Invitation Code')

@section('content')
<div id="adminInvitationCodesPage">
<div class="mb-6">
    <a href="{{ route('admin.invitation-codes.index') }}"
       class="text-sm text-zinc-500 hover:text-zinc-700 transition-colors">&larr; Invitation Codes</a>
    <h1 class="text-2xl font-semibold text-zinc-900 mt-2">Edit: <span class="font-mono">{{ $invitationCode->code }}</span></h1>
</div>

@php $isClaimed = $invitationCode->used_at !== null; @endphp

<div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg mb-6">
    <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-base font-medium text-zinc-900">Code details</h2>
        <p class="text-sm text-zinc-500 mt-0.5">
            @if($isClaimed)
                This code has been claimed — only the description can be edited.
            @else
                The code itself cannot be changed after creation.
            @endif
        </p>
    </div>

    <div class="px-6 py-5">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.invitation-codes.update', $invitationCode) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Code</label>
                <p class="font-mono text-sm text-zinc-900 bg-zinc-50 border border-zinc-200 rounded-lg px-3 py-2">
                    {{ $invitationCode->code }}
                </p>
            </div>

            @if($isClaimed)
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Claimed by</label>
                    @if($invitationCode->usedBy)
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold shrink-0">
                                {{ $invitationCode->usedBy->initials() }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $invitationCode->usedBy->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $invitationCode->usedBy->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-zinc-500">User #{{ $invitationCode->used_by }}</p>
                    @endif
                    <p class="text-xs text-zinc-400 mt-1.5">{{ $invitationCode->used_at->format('Y-m-d H:i') }}</p>
                </div>
            @endif

            <div>
                <label for="description" class="block text-sm font-medium text-zinc-700 mb-1.5">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Optional note about this code's purpose"
                    class="w-full border @error('description') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 resize-none"
                >{{ old('description', $invitationCode->description) }}</textarea>
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
                    {{ old('active', $invitationCode->active) ? 'checked' : '' }}
                    {{ $isClaimed ? 'disabled' : '' }}
                    class="h-4 w-4 rounded text-indigo-600 focus:ring-indigo-500 {{ $isClaimed ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}"
                >
                <label for="active" class="text-sm font-medium text-zinc-700 {{ $isClaimed ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                    Active
                </label>
                @if($isClaimed)
                    <span class="text-xs text-zinc-400">(locked — code has been used)</span>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Save changes
                </button>
                <a href="{{ route('admin.invitation-codes.index') }}"
                   class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Danger zone -->
<div class="bg-white rounded-xl border border-red-200 shadow-sm max-w-lg">
    <div class="px-6 py-5 border-b border-red-100">
        <h2 class="text-base font-medium text-red-700">Danger zone</h2>
    </div>
    <div class="px-6 py-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-zinc-900">Delete this code</p>
            <p class="text-sm text-zinc-500 mt-0.5">This action cannot be undone.</p>
        </div>
        <form method="POST" action="{{ route('admin.invitation-codes.destroy', $invitationCode) }}"
              data-confirm="Delete invitation code {{ $invitationCode->code }}?">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Delete
            </button>
        </form>
    </div>
</div>
</div>{{-- #adminInvitationCodesPage --}}
@endsection
