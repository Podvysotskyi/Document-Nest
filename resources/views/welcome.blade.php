<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Document Nest</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <a href="{{ route('roadmap') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 sm:inline-flex">
                    Roadmap
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800">
                        Sign in to get started
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="border-b border-zinc-200 bg-white">
            <div
                class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-7xl flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Private document
                        vault</p>
                    <h1 class="mt-4 text-4xl font-bold tracking-tight text-zinc-950 sm:text-6xl">Document Nest</h1>
                    <p class="mt-5 text-lg leading-8 text-zinc-600">
                        Securely organize your important life documents, track renewal dates, and keep IDs, policies,
                        contracts, warranties, and records easy to find when you need them.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800 sm:w-auto">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800 sm:w-auto">
                                Sign in to get started
                            </a>
                        @endauth
                        <a href="#overview"
                           class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-50 sm:w-auto">
                            See what is included
                        </a>
                        <a href="{{ route('roadmap') }}"
                           class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-50 sm:w-auto">
                            View roadmap
                        </a>
                    </div>
                </div>

                <div class="mt-12 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-950 shadow-sm">
                    <div class="grid gap-px bg-zinc-800 lg:grid-cols-[1.1fr_0.9fr]">
                        <div class="bg-white p-5 sm:p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Library
                                        overview</p>
                                    <p class="mt-1 text-xl font-semibold text-zinc-950">Documents that need
                                        attention</p>
                                </div>
                                <span
                                    class="inline-flex w-fit rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">3 expiring soon</span>
                            </div>
                            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border border-zinc-200 p-4">
                                    <p class="text-xs font-medium text-zinc-500">Total documents</p>
                                    <p class="mt-2 text-2xl font-bold text-zinc-950">48</p>
                                </div>
                                <div class="rounded-lg border border-zinc-200 p-4">
                                    <p class="text-xs font-medium text-zinc-500">Categories</p>
                                    <p class="mt-2 text-2xl font-bold text-zinc-950">9</p>
                                </div>
                                <div class="rounded-lg border border-zinc-200 p-4">
                                    <p class="text-xs font-medium text-zinc-500">Missing expiry</p>
                                    <p class="mt-2 text-2xl font-bold text-zinc-950">6</p>
                                </div>
                            </div>
                            <div class="mt-5 divide-y divide-zinc-100 rounded-lg border border-zinc-200">
                                <div class="flex items-center justify-between gap-4 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-zinc-900">Passport scan</p>
                                        <p class="text-xs text-zinc-500">Identity • expires Aug 12, 2026</p>
                                    </div>
                                    <span
                                        class="rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">Renew soon</span>
                                </div>
                                <div class="flex items-center justify-between gap-4 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-zinc-900">Home insurance
                                            policy</p>
                                        <p class="text-xs text-zinc-500">Home • tagged insurance</p>
                                    </div>
                                    <span
                                        class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                </div>
                                <div class="flex items-center justify-between gap-4 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-zinc-900">Vehicle registration</p>
                                        <p class="text-xs text-zinc-500">Vehicle • no expiry date</p>
                                    </div>
                                    <span
                                        class="rounded-full bg-zinc-100 px-2 py-1 text-xs font-semibold text-zinc-600">Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-zinc-950 p-5 text-white sm:p-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Built for personal
                                records</p>
                            <div class="mt-5 space-y-4">
                                <div>
                                    <p class="text-sm font-semibold">Upload and preview</p>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">Store PDFs and images outside the
                                        public directory, then preview or download them through authorized routes.</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold">Find the right file</p>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">Search by title, notes, or original
                                        filename. Filter by category, tags, status, and expiry date range.</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold">Track follow-up work</p>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">Dashboard sections surface recent
                                        uploads, missing expiry dates, and documents expiring soon.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="overview" class="border-b border-zinc-200 bg-zinc-50">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-950">What you can keep organized</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        Document Nest is designed for personal records that are easy to misplace and costly to overlook.
                    </p>
                </div>
                <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                    @foreach ([
                        ['Identity', 'Passports, IDs, birth certificates, immigration records.'],
                        ['Finance', 'Tax forms, bank letters, investment statements, receipts.'],
                        ['Home and vehicle', 'Insurance policies, registrations, warranties, lease records.'],
                        ['Work and legal', 'Contracts, certifications, signed documents, legal notices.'],
                    ] as [$title, $copy])
                        <div class="rounded-lg border border-zinc-200 bg-white p-5">
                            <h3 class="text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-950">How the app works</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        The workflow stays focused: upload, describe, filter, preview, and archive.
                    </p>
                </div>
                <ol class="grid gap-4 lg:col-span-2 sm:grid-cols-3">
                    @foreach ([
                        ['1', 'Upload', 'Add a PDF, JPG, PNG, or WebP file up to 20MB.'],
                        ['2', 'Describe', 'Set category, tags, status, notes, issue date, and expiry date.'],
                        ['3', 'Act', 'Preview, download, update, archive, or delete when records change.'],
                    ] as [$number, $title, $copy])
                        <li class="rounded-lg border border-zinc-200 bg-white p-5">
                            <span
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white">{{ $number }}</span>
                            <h3 class="mt-4 text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    </main>

    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>Document Nest &copy; {{ date('Y') }}</p>
            <p>Google-only sign in. Private local storage for v1.</p>
        </div>
    </footer>
</div>
</body>
</html>
