@extends('layouts.public')

@section('title', 'About • Document Nest')

@section('content')
    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">About the project</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-zinc-950 sm:text-5xl">
                    A private home for the documents that matter
                </h1>
                <p class="mt-5 text-lg leading-8 text-zinc-600">
                    Document Nest is a personal document vault built for the records you cannot afford to lose track of:
                    identity papers, insurance policies, contracts, warranties, tax forms, and the renewal dates that
                    come with them. It is designed to be private by default, fast to retrieve from, and quiet enough to
                    use on a normal week.
                </p>
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-zinc-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">What it is for</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    A focused single-user vault for the paperwork of everyday life. Document Nest is intentionally
                    smaller than a generic file host — it is shaped around the actual jobs personal documents need to
                    do.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['Store with intent', 'Upload PDFs and images, then capture the metadata that makes them findable later: category, tags, status, notes, issue date, and expiry date.'],
                    ['Retrieve in seconds', 'Search across titles, notes, and original filenames. Filter by category, tags, status, and expiry date range. Sort to surface what is changing first.'],
                    ['Stay ahead of expiry', 'A dashboard surfaces documents expiring soon, documents missing expiry dates, and recent activity so renewal work never piles up silently.'],
                    ['Archive without losing history', 'Move outdated records to an archived state instead of deleting them, and restore them if circumstances change.'],
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
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">How privacy works</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Personal documents need stricter handling than ordinary uploads. Document Nest treats authorization
                    and storage isolation as baseline requirements, not optional hardening.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['User-scoped data', 'Documents, categories, and tags are owned by a single user account. Every query is scoped to the signed-in user, and policies enforce ownership on every action.'],
                    ['Private storage', 'Uploaded files live on a private local disk outside the public web root. The frontend never receives raw storage paths.'],
                    ['Authorized previews and downloads', 'Preview and download routes pass through the same authorization checks as the rest of the app. There are no shareable public links.'],
                    ['Google-only sign in', 'Authentication is delegated to Google via Laravel Socialite, so credentials never live in the app itself.'],
                ] as [$title, $copy])
                    <div class="rounded-lg border border-zinc-200 bg-white p-5 ring-1 ring-inset ring-zinc-100">
                        <h3 class="text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-zinc-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">How it's designed to feel</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    The look and feel are kept deliberately simple. Document Nest is meant to disappear into the
                    background of your week and stay easy to come back to months later.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['Fast to open', 'Pages and previews load quickly, including on a phone, so you can grab a document on the go.'],
                    ['Quiet by default', 'No marketing emails, no notifications you did not ask for, and no public sharing surface.'],
                    ['Predictable', 'Workflows stay stable between updates. New capabilities are added without rearranging the things you already use.'],
                    ['Yours to keep', 'You can export, archive, and delete your own records. Nothing about your documents is shared outside your account.'],
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
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">What's included today</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    The current release is scoped intentionally tight: ship a trustworthy single-user vault before
                    introducing collaboration, automation, or external storage.
                </p>
            </div>
            <div class="grid gap-6 lg:col-span-2 sm:grid-cols-2">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5">
                    <h3 class="text-sm font-semibold text-emerald-900">Included today</h3>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-emerald-900">
                        @foreach ([
                            'Google-only authentication',
                            'Private document upload and storage',
                            'Category and tag organization',
                            'Document metadata: dates, status, notes, file info',
                            'PDF and image preview, including mobile',
                            'Authorized file download',
                            'Search, filters, sorting, and archive flow',
                            'Dashboard with expiring and missing-expiry visibility',
                            'Bulk archive, restore, and delete actions',
                        ] as $item)
                            <li class="flex gap-2">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                    <h3 class="text-sm font-semibold text-zinc-900">Deliberately out of scope</h3>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-zinc-700">
                        @foreach ([
                            'Multi-user, team, or household sharing',
                            'OCR and AI-assisted classification',
                            'Billing and subscription features',
                            'Detailed audit logs beyond document activity',
                            'External cloud storage such as S3',
                            'SMS, push, or calendar integrations',
                        ] as $item)
                            <li class="flex gap-2">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-zinc-400"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-zinc-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">Open source and direction</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Document Nest is released under the GNU General Public License v3.0. The product direction stays
                    public so it is clear what is shipping next and what is being held back on purpose.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                <a href="{{ route('roadmap') }}"
                   class="group rounded-lg border border-zinc-200 bg-white p-5 transition-colors hover:border-zinc-300 hover:bg-white">
                    <h3 class="text-sm font-semibold text-zinc-950 group-hover:text-emerald-700">See the roadmap</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">
                        Phased plan covering activity history, email reminders, saved filter views, bulk actions, and
                        mobile preview improvements.
                    </p>
                </a>
                <a href="{{ route('license') }}"
                   class="group rounded-lg border border-zinc-200 bg-white p-5 transition-colors hover:border-zinc-300 hover:bg-white">
                    <h3 class="text-sm font-semibold text-zinc-950 group-hover:text-emerald-700">Review the license</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">
                        Document Nest is distributed under GPL-3.0. Read the full license terms before redistributing or
                        modifying the source.
                    </p>
                </a>
            </div>
        </div>
    </section>
@endsection