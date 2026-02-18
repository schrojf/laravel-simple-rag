<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Style Guide — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-zinc-50 font-sans antialiased text-zinc-900">

<!-- Page layout -->
<div class="flex min-h-screen">

    <!-- Sidebar nav -->
    <aside class="w-56 shrink-0 bg-white border-r border-zinc-200 sticky top-0 h-screen overflow-y-auto p-4">
        <div class="flex items-center gap-2 mb-8">
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
            <span class="text-sm font-semibold text-zinc-900">Style Guide</span>
        </div>
        <nav class="space-y-1 text-sm">
            <a href="#brand" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Brand &amp; Icon</a>
            <a href="#colors" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Color Palette</a>
            <a href="#typography" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Typography</a>
            <a href="#forms" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Form Elements</a>
            <a href="#buttons" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Buttons</a>
            <a href="#cards" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Cards</a>
            <a href="#alerts" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Alerts</a>
            <a href="#badges" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Badges</a>
            <a href="#auth-preview" class="block px-2 py-1.5 rounded-md text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Auth Preview</a>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 max-w-4xl">

        <div class="mb-10">
            <h1 class="text-2xl font-semibold text-zinc-900">Design System</h1>
            <p class="text-zinc-500 mt-1">{{ config('app.name') }} — UI component reference and design tokens.</p>
        </div>

        <!-- ======================================= -->
        <!-- BRAND & ICON -->
        <!-- ======================================= -->
        <section id="brand" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Brand &amp; Icon</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6">
                <p class="text-sm text-zinc-500 mb-4">Knowledge Provider icon — book with AI sparkle (indigo-themed). Use at 24px, 32px, or 48px.</p>

                <div class="flex items-end gap-8 flex-wrap">
                    <!-- 24px -->
                    <div class="flex flex-col items-center gap-2">
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
                        <span class="text-xs text-zinc-400">24px</span>
                    </div>
                    <!-- 32px -->
                    <div class="flex flex-col items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-8 w-8">
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
                        <span class="text-xs text-zinc-400">32px</span>
                    </div>
                    <!-- 48px -->
                    <div class="flex flex-col items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-12 w-12">
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
                        <span class="text-xs text-zinc-400">48px</span>
                    </div>
                    <!-- Lockup -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-6 w-6">
                                <path d="M24 38C24 38 10 33 10 16L10 12C10 12 16 10 24 14C32 10 38 12 38 12L38 16C38 33 24 38 24 38Z"
                                      stroke="#4f46e5" stroke-width="2" stroke-linejoin="round" fill="#eef2ff"/>
                                <line x1="24" y1="14" x2="24" y2="38" stroke="#4f46e5" stroke-width="1.5"/>
                                <line x1="14" y1="19" x2="22" y2="18" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                                <line x1="26" y1="18" x2="34" y2="19" stroke="#a5b4fc" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M39 8L40 5L41 8L44 9L41 10L40 13L39 10L36 9Z"
                                      fill="#4f46e5" stroke="#4f46e5" stroke-width="0.5" stroke-linejoin="round"/>
                            </svg>
                            <span class="font-semibold text-zinc-900 text-sm">{{ config('app.name') }}</span>
                        </div>
                        <span class="text-xs text-zinc-400">Nav lockup (24px icon + app name)</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- COLOR PALETTE -->
        <!-- ======================================= -->
        <section id="colors" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Color Palette</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-6">
                <!-- Zinc scale -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Zinc (neutrals)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['50' => 'bg-zinc-50', '100' => 'bg-zinc-100', '200' => 'bg-zinc-200', '300' => 'bg-zinc-300', '500' => 'bg-zinc-500', '700' => 'bg-zinc-700', '900' => 'bg-zinc-900'] as $label => $cls)
                            <div class="flex flex-col items-center gap-1">
                                <div class="{{ $cls }} h-10 w-10 rounded-lg border border-zinc-200"></div>
                                <span class="text-xs text-zinc-400">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Indigo scale -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Indigo (primary)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['50' => 'bg-indigo-50', '100' => 'bg-indigo-100', '300' => 'bg-indigo-300', '600' => 'bg-indigo-600', '700' => 'bg-indigo-700'] as $label => $cls)
                            <div class="flex flex-col items-center gap-1">
                                <div class="{{ $cls }} h-10 w-10 rounded-lg border border-zinc-200"></div>
                                <span class="text-xs text-zinc-400">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Green scale -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Green (success)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['50' => 'bg-green-50', '200' => 'bg-green-200', '800' => 'bg-green-800'] as $label => $cls)
                            <div class="flex flex-col items-center gap-1">
                                <div class="{{ $cls }} h-10 w-10 rounded-lg border border-zinc-200"></div>
                                <span class="text-xs text-zinc-400">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Red scale -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Red (error / destructive)</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['50' => 'bg-red-50', '200' => 'bg-red-200', '500' => 'bg-red-500', '600' => 'bg-red-600', '800' => 'bg-red-800'] as $label => $cls)
                            <div class="flex flex-col items-center gap-1">
                                <div class="{{ $cls }} h-10 w-10 rounded-lg border border-zinc-200"></div>
                                <span class="text-xs text-zinc-400">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- TYPOGRAPHY -->
        <!-- ======================================= -->
        <section id="typography" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Typography</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">h1 — text-2xl font-semibold text-zinc-900</p>
                    <p class="text-2xl font-semibold text-zinc-900">Knowledge Base Heading</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">h2 — text-xl font-semibold text-zinc-900</p>
                    <p class="text-xl font-semibold text-zinc-900">Section Heading</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">h3 — text-lg font-semibold text-zinc-900</p>
                    <p class="text-lg font-semibold text-zinc-900">Card Title</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">h4 — text-base font-medium text-zinc-900</p>
                    <p class="text-base font-medium text-zinc-900">Subsection Label</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">body — text-sm text-zinc-700</p>
                    <p class="text-sm text-zinc-700">This is body text used for descriptions and content across the application. Instrument Sans keeps it clean and readable.</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">small — text-xs text-zinc-500</p>
                    <p class="text-xs text-zinc-500">Helper text, timestamps, metadata</p>
                </div>
                <div class="pb-4 border-b border-zinc-100">
                    <p class="text-xs text-zinc-400 mb-1">muted — text-sm text-zinc-500</p>
                    <p class="text-sm text-zinc-500">Secondary descriptions and subtitles.</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 mb-1">link — text-sm text-indigo-600 hover:text-indigo-700 font-medium</p>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View knowledge entry →</a>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- FORM ELEMENTS -->
        <!-- ======================================= -->
        <section id="forms" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Form Elements</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-6 max-w-sm">
                <!-- Text input normal -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Text input (normal)</label>
                    <input type="text" placeholder="Enter a value" class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Text input error -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Text input (error state)</label>
                    <input type="text" value="invalid@" class="w-full border border-red-500 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-red-600 text-sm mt-1">This field is required.</p>
                </div>

                <!-- Password input -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Password input</label>
                    <input type="password" placeholder="••••••••" class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Readonly input -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Readonly input</label>
                    <input type="email" value="user@example.com" readonly class="w-full border border-zinc-200 rounded-lg px-3 py-2 text-sm text-zinc-500 bg-zinc-50 cursor-not-allowed">
                </div>

                <!-- Textarea -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Textarea</label>
                    <textarea rows="3" placeholder="Describe your knowledge snippet..." class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                </div>

                <!-- Checkbox -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="sg-check" class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="sg-check" class="text-sm text-zinc-600">Remember me on this device</label>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- BUTTONS -->
        <!-- ======================================= -->
        <section id="buttons" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Buttons</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-6">
                <!-- Primary -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Primary</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Save changes
                        </button>
                        <button class="w-48 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Full-width variant
                        </button>
                        <button disabled class="bg-indigo-600 text-white font-medium text-sm py-2 px-4 rounded-lg opacity-50 cursor-not-allowed">
                            Disabled
                        </button>
                    </div>
                </div>

                <!-- Outline / Secondary -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Secondary / Outline</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <button class="border border-zinc-300 bg-white hover:bg-zinc-50 text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button class="w-48 border border-zinc-300 bg-white hover:bg-zinc-50 text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Full-width variant
                        </button>
                        <button disabled class="border border-zinc-300 bg-white text-zinc-700 font-medium text-sm py-2 px-4 rounded-lg opacity-50 cursor-not-allowed">
                            Disabled
                        </button>
                    </div>
                </div>

                <!-- Destructive -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Destructive</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <button class="bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                            Delete entry
                        </button>
                        <button disabled class="bg-red-600 text-white font-medium text-sm py-2 px-4 rounded-lg opacity-50 cursor-not-allowed">
                            Disabled
                        </button>
                    </div>
                </div>

                <!-- Text/link button -->
                <div>
                    <p class="text-sm font-medium text-zinc-700 mb-3">Inline link button</p>
                    <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors cursor-pointer">
                        View all entries →
                    </button>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- CARDS -->
        <!-- ======================================= -->
        <section id="cards" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Cards</p>

            <div class="space-y-4">
                <!-- Plain card -->
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                    <p class="text-sm font-medium text-zinc-700 mb-1">Plain card</p>
                    <p class="text-sm text-zinc-500">bg-white rounded-xl border border-zinc-200 shadow-sm — the standard container used across the application.</p>
                </div>

                <!-- Card with header / content / footer -->
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100">
                        <p class="text-base font-medium text-zinc-900">Knowledge Entry</p>
                        <p class="text-sm text-zinc-500 mt-0.5">Added February 2026</p>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-zinc-700">This is the card content area. Use it for the main body of information, document excerpts, or context snippets that will be provided to the LLM.</p>
                    </div>
                    <div class="px-6 py-3 bg-zinc-50 border-t border-zinc-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400">Last updated 2 days ago</span>
                        <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium cursor-pointer">Edit</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- ALERTS -->
        <!-- ======================================= -->
        <section id="alerts" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Alerts</p>

            <div class="space-y-3">
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                    <strong class="font-medium">Success:</strong> Your password reset link has been sent to your email address.
                </div>
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3">
                    <strong class="font-medium">Error:</strong> These credentials do not match our records.
                </div>
                <div class="bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-lg px-4 py-3">
                    <strong class="font-medium">Info:</strong> Please verify your email address before accessing the knowledge base.
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- BADGES -->
        <!-- ======================================= -->
        <section id="badges" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Badges</p>

            <div class="bg-white rounded-xl border border-zinc-200 p-6">
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">AI Context</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Error</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-700">Draft</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-600 text-white">MCP</span>
                </div>
            </div>
        </section>

        <!-- ======================================= -->
        <!-- AUTH CARD PREVIEW -->
        <!-- ======================================= -->
        <section id="auth-preview" class="mb-14 scroll-mt-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 mb-5">Auth Card Preview</p>

            <div class="bg-zinc-100 rounded-xl border border-zinc-200 p-8 flex flex-col items-center">
                <!-- Branding above card (preview) -->
                <div class="mb-8 flex flex-col items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="h-12 w-12">
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
                    <span class="text-xl font-semibold text-zinc-900 tracking-tight">{{ config('app.name') }}</span>
                </div>

                <!-- Auth card mock -->
                <div class="w-full max-w-sm bg-white rounded-xl border border-zinc-200 shadow-sm">
                    <div class="p-6 sm:p-8">
                        <div class="mb-6">
                            <h1 class="text-lg font-semibold text-zinc-900">Sign in to your account</h1>
                            <p class="text-sm text-zinc-500 mt-1">Welcome back. Enter your credentials to continue.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Email address</label>
                                <input type="email" placeholder="you@example.com" class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-sm font-medium text-zinc-700">Password</label>
                                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Forgot password?</a>
                                </div>
                                <input type="password" placeholder="••••••••" class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="sg-remember" class="h-4 w-4 rounded border-zinc-300 text-indigo-600">
                                <label for="sg-remember" class="text-sm text-zinc-600">Remember me</label>
                            </div>
                            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition-colors cursor-pointer">
                                Sign in
                            </button>
                        </div>
                        <p class="text-center text-sm text-zinc-500 mt-6">
                            Don't have an account?
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Create one</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
