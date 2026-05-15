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

    @hasSection('footer')
        <footer class="border-t border-zinc-200 bg-zinc-50">
            @yield('footer')
        </footer>
    @endif
</div>
</body>
</html>
