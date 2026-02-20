<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-zinc-50 font-sans antialiased flex flex-col">
    <header class="bg-white border-b border-zinc-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-6 w-6">
                    <path d="M24 38C24 38 10 33 10 16L10 12C10 12 16 10 24 14C32 10 38 12 38 12L38 16C38 33 24 38 24 38Z"
                          stroke="#4f46e5" stroke-width="2" stroke-linejoin="round" fill="#eef2ff"/>
                    <line x1="24" y1="14" x2="24" y2="38" stroke="#4f46e5" stroke-width="1.5"/>
                    <line x1="14" y1="19" x2="22" y2="18" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="14" y1="23" x2="22" y2="22" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="14" y1="27" x2="22" y2="26" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="18" x2="34" y2="19" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="22" x2="34" y2="23" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="26" x2="34" y2="27" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M39 8L40 5L41 8L44 9L41 10L40 13L39 10L36 9Z"
                          fill="#4f46e5" stroke="#4f46e5" stroke-width="0.5" stroke-linejoin="round"/>
                </svg>
                <span class="font-semibold text-zinc-900 text-sm">{{ config('app.name') }}</span>
            </a>

            @include('pages._partials.header')
        </div>
    </header>
    @include('pages._partials.navigation')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full">
        @yield('content')
    </main>
    @include('pages._partials.footer')
</body>
</html>
