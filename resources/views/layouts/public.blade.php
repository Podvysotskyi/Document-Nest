<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Document Nest')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    @vite('resources/css/app.css')
</head>
<body class="min-h-full bg-zinc-50 font-sans text-zinc-900 antialiased">
<div class="min-h-screen bg-zinc-50">
    <header class="border-b border-zinc-200 bg-white/90">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a class="flex items-center gap-2 font-bold tracking-tight text-zinc-900" href="{{ url('/') }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-6a2.25 2.25 0 0 0-2.25-2.25h-3.879a2.25 2.25 0 0 1-1.59-.659L9.659 3.22A2.25 2.25 0 0 0 8.068 2.56H6.75A2.25 2.25 0 0 0 4.5 4.81v14.44a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5h6M9 16.5h4.5"/>
                    </svg>
                </span>
                <span>Document Nest</span>
            </a>

            <nav class="flex items-center gap-2">
                @foreach ([
                    ['label' => 'Welcome', 'url' => url('/'), 'active' => request()->is('/')],
                    ['label' => 'About', 'url' => route('about'), 'active' => request()->routeIs('about')],
                    ['label' => 'License', 'url' => route('license'), 'active' => request()->routeIs('license')],
                    ['label' => 'Roadmap', 'url' => route('roadmap'), 'active' => request()->routeIs('roadmap')],
                ] as $navigationLink)
                    <a href="{{ $navigationLink['url'] }}"
                       class="{{ $navigationLink['active'] ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }} hidden rounded-lg px-3 py-2 text-sm font-semibold transition-colors sm:inline-flex">
                        {{ $navigationLink['label'] }}
                    </a>
                @endforeach

                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        @yield('auth-action-label', 'Sign in')
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-3">
                <p>Document Nest &copy; {{ date('Y') }}</p>
                <span class="hidden text-zinc-300 sm:inline">&middot;</span>
                <p>
                    Developed by
                    <span class="font-medium text-zinc-600 transition-colors">Serhii Podvysotskyi</span>
                </p>
            </div>
            <nav class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <a class="inline-flex items-center gap-1.5 font-medium text-zinc-600 transition-colors hover:text-zinc-900"
                   href="https://github.com/Podvysotskyi/Document-Nest"
                   target="_blank" rel="noopener noreferrer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z"/>
                    </svg>
                    GitHub
                </a>
                <a class="inline-flex items-center gap-1.5 font-medium text-zinc-600 transition-colors hover:text-zinc-900"
                   href="https://www.linkedin.com/in/podvysotskyi/"
                   target="_blank" rel="noopener noreferrer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.852 3.37-1.852 3.602 0 4.268 2.37 4.268 5.455v6.288zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.063 2.063 0 1 1 2.063 2.065zm1.78 13.019H3.555V9h3.562v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    LinkedIn
                </a>
            </nav>
        </div>
    </footer>
</div>
</body>
</html>
