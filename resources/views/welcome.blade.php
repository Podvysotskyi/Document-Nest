@extends('layouts.public')

@section('title', 'Document Nest')
@section('auth-action-label', 'Sign in to get started')

@section('content')
    <section class="border-b border-zinc-200 bg-white">
        <div
            class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-7xl flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Private document vault</p>
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
                                <p class="mt-1 text-xl font-semibold text-zinc-950">Documents that need attention</p>
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
                                    <p class="truncate text-sm font-semibold text-zinc-900">Home insurance policy</p>
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

    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Why it exists</p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-zinc-950">Personal paperwork should not be
                    scattered</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Most renewal misses do not come from forgetting a document exists. They come from not knowing where
                    the latest copy lives, when it expires, or whether the version on your laptop is current.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-3">
                @foreach ([
                    ['One private place', 'No more searching across email attachments, downloads folders, and photo albums for a passport scan or a renewal letter.'],
                    ['Renewal-aware', 'Expiry dates are first-class metadata, not a comment buried in a filename. The dashboard surfaces what changes first.'],
                    ['Quiet by default', 'No notifications you did not ask for, no public links, no shareable URLs. It stays a private vault until you decide otherwise.'],
                ] as [$title, $copy])
                    <div class="rounded-lg border border-zinc-200 bg-white p-5">
                        <h3 class="text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                    </div>
                @endforeach
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

    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">Features that ship in v1</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Each feature is built around a job a single person actually does with personal paperwork — not
                    around storage volume or generic file management.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    [
                        'label' => 'Upload',
                        'title' => 'PDFs and images up to 20MB',
                        'copy' => 'Accept PDF, JPG, PNG, and WebP. Files are stored on a private disk outside the public web root.',
                    ],
                    [
                        'label' => 'Organize',
                        'title' => 'Categories, tags, and status',
                        'copy' => 'Group by category, attach tags, and mark documents active or archived without losing history.',
                    ],
                    [
                        'label' => 'Track',
                        'title' => 'Issue and expiry dates',
                        'copy' => 'Capture when a document was issued and when it expires. Missing expiry dates show up on the dashboard.',
                    ],
                    [
                        'label' => 'Preview',
                        'title' => 'Mobile-friendly previews',
                        'copy' => 'Inline PDF and image preview that works on a phone, served only through authorized routes.',
                    ],
                    [
                        'label' => 'Find',
                        'title' => 'Search, filter, and sort',
                        'copy' => 'Search across titles, notes, and original filenames. Filter by category, tags, status, and expiry range.',
                    ],
                    [
                        'label' => 'Maintain',
                        'title' => 'Bulk actions and archive',
                        'copy' => 'Archive, restore, or delete multiple documents at once. Restore archived records when circumstances change.',
                    ],
                ] as $feature)
                    <article class="rounded-lg border border-zinc-200 bg-white p-5 shadow-xs">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">{{ $feature['label'] }}</p>
                        <h3 class="mt-2 text-base font-semibold tracking-tight text-zinc-950">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $feature['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-zinc-50">
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

    <section class="border-b border-zinc-200 bg-zinc-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Trust model</p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-white">Privacy is the default, not a setting</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-300">
                    Document Nest treats authorization and storage isolation as baseline requirements. There is no
                    public sharing surface in v1 — the only way to reach a file is through an authenticated session
                    that owns it.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['User-scoped data', 'Every document, category, and tag belongs to a single account. Queries and policies enforce ownership on every action.'],
                    ['Private local storage', 'Files live outside the public web root. The frontend never receives raw storage paths or shareable URLs.'],
                    ['Google sign in only', 'Authentication is delegated to Google via Laravel Socialite. Credentials never live inside the app.'],
                    ['Open source', 'GPL-3.0 licensed. You can read the source, self-host it, and audit how it handles your records.'],
                ] as [$title, $copy])
                    <div class="rounded-lg border border-zinc-800 bg-zinc-900 p-5">
                        <h3 class="text-sm font-semibold text-white">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-6 py-12 sm:px-12 sm:py-14">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-950">Start with your most-renewed documents
                    </h2>
                    <p class="mt-4 text-base leading-7 text-zinc-600">
                        Sign in with Google, upload the records you renew most often, and let the dashboard tell you
                        what is coming up. The rest can move in over time.
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
                        <a href="{{ route('about') }}"
                           class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-100 sm:w-auto">
                            Read more about the project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection