@extends('layouts.auth')
@section('title', 'Two-factor authentication')

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-zinc-900">Two-factor authentication</h1>
        <p class="text-sm text-zinc-500 mt-1">Confirm access to your account.</p>
    </div>

    <!-- Tab toggle -->
    <div class="flex border-b border-zinc-200 mb-6 gap-4">
        <button
            id="tab-code"
            type="button"
            class="pb-2.5 text-sm font-medium text-zinc-900 border-b-2 border-indigo-600 -mb-px transition-colors cursor-pointer"
            onclick="switchToCode()"
        >
            Authentication code
        </button>
        <button
            id="tab-recovery"
            type="button"
            class="pb-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 border-b-2 border-transparent -mb-px transition-colors cursor-pointer"
            onclick="switchToRecovery()"
        >
            Recovery code
        </button>
    </div>

    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
        @csrf

        <!-- Authentication code section -->
        <div id="code-section">
            <label for="code" class="block text-sm font-medium text-zinc-700 mb-1.5">6-digit code</label>
            <p class="text-sm text-zinc-500 mb-3">Enter the code from your authenticator app.</p>
            <input
                type="text"
                id="code"
                name="code"
                inputmode="numeric"
                maxlength="6"
                autofocus
                autocomplete="one-time-code"
                placeholder="000000"
                class="w-full border @error('code') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent tracking-widest text-center"
            >
            @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Recovery code section -->
        <div id="recovery-section" class="hidden">
            <label for="recovery_code" class="block text-sm font-medium text-zinc-700 mb-1.5">Recovery code</label>
            <p class="text-sm text-zinc-500 mb-3">Enter one of your emergency recovery codes.</p>
            <input
                type="text"
                id="recovery_code"
                name="recovery_code"
                disabled
                autocomplete="off"
                placeholder="xxxx-xxxx-xxxx-xxxx"
                class="w-full border @error('recovery_code') border-red-500 @else border-zinc-300 @enderror rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono"
            >
            @error('recovery_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
            Verify
        </button>
    </form>
</div>

<script>
    const codeSection = document.getElementById('code-section');
    const recoverySection = document.getElementById('recovery-section');
    const tabCode = document.getElementById('tab-code');
    const tabRecovery = document.getElementById('tab-recovery');
    const codeInput = document.getElementById('code');
    const recoveryInput = document.getElementById('recovery_code');

    const activeTabClasses = ['text-zinc-900', 'border-indigo-600'];
    const inactiveTabClasses = ['text-zinc-500', 'hover:text-zinc-700', 'border-transparent'];

    function switchToCode() {
        recoverySection.classList.add('hidden');
        codeSection.classList.remove('hidden');
        recoveryInput.disabled = true;
        codeInput.disabled = false;
        codeInput.focus();

        tabCode.classList.add(...activeTabClasses);
        tabCode.classList.remove(...inactiveTabClasses);
        tabRecovery.classList.add(...inactiveTabClasses);
        tabRecovery.classList.remove(...activeTabClasses);
    }

    function switchToRecovery() {
        codeSection.classList.add('hidden');
        recoverySection.classList.remove('hidden');
        codeInput.disabled = true;
        recoveryInput.disabled = false;
        recoveryInput.focus();

        tabRecovery.classList.add(...activeTabClasses);
        tabRecovery.classList.remove(...inactiveTabClasses);
        tabCode.classList.add(...inactiveTabClasses);
        tabCode.classList.remove(...activeTabClasses);
    }
</script>
@endsection
