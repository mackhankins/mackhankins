<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? 'Mack Hankins — Developer & Creator. Building tools that matter.' }}">

    <title>{{ isset($title) ? $title . ' — Mack Hankins' : 'Mack Hankins — Developer & Creator' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('feed::links')
</head>
<body class="grain min-h-screen flex flex-col">
    {{-- Navigation --}}
    <nav class="fixed top-0 inset-x-0 z-40 border-b border-base-700/50 bg-base-900/80 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display font-bold text-lg tracking-tight text-base-50 hover:text-amber-accent transition-colors">
                Mack Hankins
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('projects.index') }}"
                   class="nav-link text-sm font-display font-medium text-base-300 {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    Stuff
                </a>
                <a href="{{ route('blog.index') }}"
                   class="nav-link text-sm font-display font-medium text-base-300 {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    Things
                </a>
                <a href="{{ route('about') }}"
                   class="nav-link text-sm font-display font-medium text-base-300 {{ request()->routeIs('about') ? 'active' : '' }}">
                    About
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button id="mobile-nav-toggle" class="md:hidden text-base-300 hover:text-base-50 transition-colors" aria-label="Toggle navigation" aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-nav-menu" class="hidden md:hidden border-t border-base-700/50 bg-base-900/95 backdrop-blur-xl">
            <div class="px-6 py-4 flex flex-col gap-3">
                <a href="{{ route('projects.index') }}" class="text-sm font-display font-medium text-base-300 hover:text-base-50 transition-colors py-1">Stuff</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-display font-medium text-base-300 hover:text-base-50 transition-colors py-1">Things</a>
                <a href="{{ route('about') }}" class="text-sm font-display font-medium text-base-300 hover:text-base-50 transition-colors py-1">About</a>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="flex-1 pt-16">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-base-700/50 mt-auto">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <a href="https://github.com/mackhankins" target="_blank" rel="noopener noreferrer"
                       class="text-base-400 hover:text-amber-accent transition-colors" aria-label="GitHub">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    </a>
                    <a href="https://x.com/mackhankins" target="_blank" rel="noopener noreferrer"
                       class="text-base-400 hover:text-amber-accent transition-colors" aria-label="X / Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/in/mack-hankins/" target="_blank" rel="noopener noreferrer"
                       class="text-base-400 hover:text-amber-accent transition-colors" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>

                <p class="text-sm text-base-400 font-body">
                    &copy; {{ date('Y') }} Mack Hankins. Built with Laravel & care.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
