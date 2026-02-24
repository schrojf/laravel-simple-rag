@php $currentRoute = Route::currentRouteName(); @endphp
<div class="flex border-b border-zinc-200 mb-8 gap-4">
    <a href="{{ route('settings.profile') }}"
       class="pb-2.5 text-sm font-medium border-b-2 -mb-px transition-colors {{ $currentRoute === 'settings.profile' ? 'border-indigo-600 text-zinc-900' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
        Profile
    </a>
    <a href="{{ route('settings.password') }}"
       class="pb-2.5 text-sm font-medium border-b-2 -mb-px transition-colors {{ $currentRoute === 'settings.password' ? 'border-indigo-600 text-zinc-900' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
        Password
    </a>
    <a href="{{ route('two-factor.show') }}"
       class="pb-2.5 text-sm font-medium border-b-2 -mb-px transition-colors {{ $currentRoute === 'two-factor.show' ? 'border-indigo-600 text-zinc-900' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
        Two-Factor
    </a>
    <a href="{{ route('settings.tokens') }}"
       class="pb-2.5 text-sm font-medium border-b-2 -mb-px transition-colors {{ $currentRoute === 'settings.tokens' ? 'border-indigo-600 text-zinc-900' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
        API Tokens
    </a>
</div>
