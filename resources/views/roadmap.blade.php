<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Roadmap - Document Nest</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    @vite('resources/css/app.css')
</head>
<body class="min-h-full font-sans text-zinc-900 antialiased">
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
                <a href="{{ url('/') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 sm:inline-flex">
                    Welcome
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        Sign in
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="border-b border-zinc-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Project roadmap</p>
                    <h1 class="mt-4 text-4xl font-bold tracking-tight text-zinc-950 sm:text-5xl">
                        Where Document Nest is headed
                    </h1>
                    <p class="mt-5 text-lg leading-8 text-zinc-600">
                        This roadmap keeps the product direction visible: finish the private document vault first, then
                        build into reminders, shared household workflows, and automation.
                    </p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-5 lg:grid-cols-2">
                @foreach ($phases as $phase)
                    <article class="rounded-lg border border-zinc-200 bg-white p-6 shadow-xs">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ $phase['label'] }}</p>
                                <h2 class="mt-2 text-xl font-semibold tracking-tight text-zinc-950">{{ $phase['title'] }}</h2>
                            </div>
                            <span
                                class="inline-flex w-fit rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                                {{ $phase['status'] }}
                            </span>
                        </div>

                        <ul class="mt-5 space-y-3">
                            @foreach ($phase['items'] as $item)
                                <li class="flex gap-3 text-sm leading-6 text-zinc-700">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600"></span>
                                    <span>{{ $item['title'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="border-t border-zinc-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-950">How priorities are chosen</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        Features stay on the roadmap when they improve trust, retrieval speed, or real follow-up work
                        around personal documents.
                    </p>
                </div>
                <div class="grid gap-4 lg:col-span-2 sm:grid-cols-3">
                    @foreach ([
                        ['Secure first', 'Authorization, private storage, and scoped data access remain the baseline.'],
                        ['Useful daily', 'Search, filters, reminders, and dashboard signals should reduce manual tracking.'],
                        ['Simple to adopt', 'The app should stay usable for one person before adding shared workflows.'],
                    ] as [$title, $copy])
                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                            <h3 class="text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
