@extends('layouts.app')
@section('title', 'Two-Factor Authentication')

@section('content')
<div id="twoFactorPage">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-zinc-900">Settings</h1>
    </div>

    @include('pages.settings.navigation')

    @if (session('status') === 'recovery-codes-generated')
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
            Recovery codes regenerated successfully.
        </div>
    @endif

    @if ($twoFactorEnabled)
        {{-- State C: Enabled --}}
        <div class="space-y-6 max-w-lg">
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
                <div class="px-6 py-5 border-b border-zinc-100">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-medium text-zinc-900">Two-Factor Authentication</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Enabled
                        </span>
                    </div>
                    <p class="text-sm text-zinc-500 mt-0.5">
                        With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.
                    </p>
                </div>

                <div class="px-6 py-5">
                    @include('pages.settings.two-factor.recovery-codes', ['recoveryCodes' => $recoveryCodes])
                </div>
            </div>

            <div class="bg-white rounded-xl border border-red-200 shadow-sm">
                <div class="px-6 py-5 border-b border-red-100">
                    <h2 class="text-base font-medium text-zinc-900">Disable Two-Factor Authentication</h2>
                    <p class="text-sm text-zinc-500 mt-0.5">Disabling two-factor authentication will remove the extra layer of security from your account.</p>
                </div>
                <div class="px-6 py-5">
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Disable 2FA
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @elseif ($setupPending)
        {{-- State B: Setup pending (secret set, not yet confirmed) --}}
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
            <div class="px-6 py-5 border-b border-zinc-100">
                <h2 class="text-base font-medium text-zinc-900">Finish setting up two-factor authentication</h2>
                <p class="text-sm text-zinc-500 mt-0.5">
                    Scan the QR code below with your authenticator app, or enter the setup key manually. Then enter the 6-digit code from your app to confirm.
                </p>
            </div>

            <div class="px-6 py-5 space-y-6">
                @if ($qrCodeSvg)
                    <div class="flex justify-center">
                        <div class="bg-white border border-zinc-200 rounded-lg p-3 inline-block">
                            {!! $qrCodeSvg !!}
                        </div>
                    </div>
                @endif

                @if ($setupKey)
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1.5">Setup key (manual entry)</label>
                        <div class="flex items-stretch border border-zinc-300 rounded-lg overflow-hidden">
                            <input
                                id="setupKeyInput"
                                type="text"
                                readonly
                                value="{{ $setupKey }}"
                                class="w-full px-3 py-2 text-sm font-mono bg-zinc-50 text-zinc-900 outline-none"
                            />
                            <button
                                id="copySetupKeyBtn"
                                type="button"
                                class="px-3 border-l border-zinc-300 bg-zinc-50 hover:bg-zinc-100 text-zinc-500 transition-colors cursor-pointer"
                                title="Copy to clipboard"
                            >
                                <svg id="copyIcon" xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                </svg>
                                <svg id="copiedIcon" xmlns="http://www.w3.org/2000/svg" class="size-4 text-green-500 hidden" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="code" class="block text-sm font-medium text-zinc-700 mb-1.5">Authentication code</label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            required
                            autofocus
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="000000"
                            value="{{ old('code') }}"
                            class="w-full border @error('code') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 font-mono tracking-widest"
                        >
                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Confirm
                        </button>
                    </div>
                </form>

                <div class="border-t border-zinc-100 pt-4">
                    <p class="text-sm text-zinc-500 mb-3">Want to start over?</p>
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-zinc-500 hover:text-zinc-700 underline cursor-pointer">
                            Cancel setup
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @else
        {{-- State A: Disabled --}}
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm max-w-lg">
            <div class="px-6 py-5 border-b border-zinc-100">
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-medium text-zinc-900">Two-Factor Authentication</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Disabled
                    </span>
                </div>
                <p class="text-sm text-zinc-500 mt-0.5">
                    When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.
                </p>
            </div>

            <div class="px-6 py-5">
                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                        Enable 2FA
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
