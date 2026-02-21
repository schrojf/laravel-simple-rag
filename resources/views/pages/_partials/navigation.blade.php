<nav class="bg-white border-b border-zinc-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center gap-1 h-10">
        <a href="{{ route('dashboard') }}"
           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
            Dashboard
        </a>

        <a href="{{ route('entries.index') }}"
           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('entries.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
            Entries
        </a>

        <a href="{{ route('entry-types.index') }}"
           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('entry-types.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
            Types
        </a>

        <a href="{{ route('topics.index') }}"
           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('topics.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
            Topics
        </a>

        @if(auth()->user()?->isAdmin())
            <a href="{{ route('admin.users.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Users
            </a>
            <a href="{{ route('admin.invitation-codes.index') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.invitation-codes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                Invitation Codes
            </a>
        @endif
    </div>
</nav>
