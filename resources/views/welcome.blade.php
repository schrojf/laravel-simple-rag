<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/landing.css', 'resources/js/landing.ts'])
    </head>
    <body class="h-full bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased">

        {{-- Navigation --}}
        <header class="absolute inset-x-0 top-0 flex items-center justify-end px-6 py-5 sm:px-8">
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors px-3 py-2">
                            Sign in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                Get access
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        {{-- Main --}}
        <main class="flex min-h-full flex-col items-center justify-center px-6 py-24 sm:py-32">

            {{-- Hero --}}
            <div class="text-center" data-landing-animate>
                <div class="mb-6 inline-flex items-center justify-center size-14 rounded-2xl bg-indigo-100 dark:bg-indigo-950 ring-1 ring-indigo-200 dark:ring-indigo-900">
                    <svg class="size-7 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>

                <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100 sm:text-4xl">
                    {{ config('app.name') }}
                </h1>

                <p class="mt-3 text-base text-zinc-500 dark:text-zinc-400">
                    Private application. Authorised access only.
                </p>

                @auth
                    <div class="mt-8">
                        <a href="{{ url('/dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors">
                            Go to dashboard
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @else
                    @if (Route::has('login'))
                        <div class="mt-8">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                Sign in
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Feature details (gated) --}}
            @if (config('app.show_landing_details'))
                <div class="mt-16 w-full max-w-2xl" data-landing-animate-delay>
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">What this does</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-zinc-100 dark:divide-zinc-800">
                            <div class="px-6 py-6" data-landing-animate-delay>
                                <div class="mb-3 inline-flex size-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950">
                                    <svg class="size-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Capture</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Store notes, documents, and ideas in one place.</p>
                            </div>
                            <div class="px-6 py-6" data-landing-animate-delay>
                                <div class="mb-3 inline-flex size-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950">
                                    <svg class="size-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Organise</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Tag and categorise content for easy retrieval.</p>
                            </div>
                            <div class="px-6 py-6" data-landing-animate-delay-2>
                                <div class="mb-3 inline-flex size-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950">
                                    <svg class="size-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Retrieve</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Find what you need, when you need it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </main>

        {{-- Footer --}}
        <footer class="absolute inset-x-0 bottom-0 flex items-center justify-center px-6 py-5">
            <p class="text-xs text-zinc-400 dark:text-zinc-600">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </p>
        </footer>

    </body>
</html>
