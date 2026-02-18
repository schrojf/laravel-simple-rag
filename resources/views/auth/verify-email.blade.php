@extends('layouts.auth')
@section('title', 'Verify email')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Verify your email</h1>
        <p class="text-sm text-zinc-500 mt-1">One more step before accessing your knowledge base.</p>
    </div>

    <div class="bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-lg px-4 py-3 mb-4">
        Thanks for signing up! Please verify your email address by clicking the link we sent you. If you didn't receive it, we can send another.
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full border border-zinc-300 bg-white hover:bg-zinc-50 text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                Sign out
            </button>
        </form>
    </div>
</div>
@endsection
