<nav class="bg-white border-b border-zinc-200">
    <div class="max-w-5xl mx-auto relative">
        <div id="navFadeLeft"
             class="hidden absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none z-10"></div>
        <div id="navFadeRight"
             class="hidden absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none z-10"></div>
        <div id="navScrollArea" class="flex items-center gap-1 h-10 overflow-x-auto px-4 sm:px-6 nav-scrollbar-hidden">
            <a href="{{ route('dashboard') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Dashboard
            </a>

            <a href="{{ route('entries.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('entries.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Entries
            </a>

            <a href="{{ route('entry-types.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('entry-types.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Types
            </a>

            <a href="{{ route('topics.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('topics.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Topics
            </a>

            <a href="{{ route('mcp-logs.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('mcp-logs.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                MCP Logs
            </a>

            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                    Users
                </a>
                <a href="{{ route('admin.invitation-codes.index') }}"
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer shrink-0 {{ request()->routeIs('admin.invitation-codes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                    Invitation Codes
                </a>
            @endif
        </div>
    </div>
</nav>
