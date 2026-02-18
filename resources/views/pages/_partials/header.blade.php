<div class="flex items-center gap-3">
    <a href="{{ route('settings.profile') }}"
       title="{{ auth()->user()->name }}"
       class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold hover:bg-indigo-200 transition-colors cursor-pointer">
        {{ auth()->user()->initials() }}
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm text-zinc-600 hover:text-zinc-900 font-medium transition-colors cursor-pointer">
            Sign out
        </button>
    </form>
</div>
