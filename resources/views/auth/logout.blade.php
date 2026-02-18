@extends('layouts.app')
@section('title', 'Sign out')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-sm bg-white rounded-xl border border-zinc-200 shadow-sm p-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
            </div>
        </div>

        <h1 class="text-lg font-semibold text-zinc-900 mb-1">Sign out</h1>
        <p class="text-sm text-zinc-500 mb-6">Are you sure you want to sign out of your account?</p>

        <div class="space-y-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Sign out
                </button>
            </form>

            <a href="{{ url()->previous('/') }}" class="block w-full border border-zinc-300 bg-white hover:bg-zinc-50 text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Cancel
            </a>
        </div>
    </div>
</div>
@endsection
