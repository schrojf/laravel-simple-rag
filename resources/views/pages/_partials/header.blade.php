<div class="relative" id="userMenu">
    <button
        id="userMenuButton"
        title="{{ auth()->user()->name }}"
        aria-haspopup="true"
        aria-expanded="false"
        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-semibold hover:bg-indigo-200 transition-colors cursor-pointer">
        {{ auth()->user()->initials() }}
    </button>

    <div id="userMenuDropdown"
         class="hidden absolute right-0 top-full mt-1.5 w-52 bg-white rounded-lg border border-zinc-200 shadow-lg py-1 z-50"
         role="menu">
        <div class="px-3 py-2 border-b border-zinc-100">
            <p class="text-sm font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</p>
        </div>
        <a href="{{ route('settings.profile') }}"
           class="block px-3 py-1.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors"
           role="menuitem">Settings</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full text-left px-3 py-1.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors cursor-pointer"
                    role="menuitem">Sign out</button>
        </form>
    </div>
</div>
