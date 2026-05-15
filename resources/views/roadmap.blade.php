@extends('layouts.public')

@section('title', 'Roadmap - Document Nest')

@section('content')
    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Project roadmap</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-zinc-950 sm:text-5xl">
                    Where Document Nest is headed
                </h1>
                <p class="mt-5 text-lg leading-8 text-zinc-600">
                    The roadmap is public so it is always clear what is shipping next, what is being saved for later,
                    and what is deliberately being left out. Each phase below is a slice of work the project is
                    committed to in order.
                </p>
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-zinc-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">How to read this page</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Phases are listed in priority order. A phase wraps when its items are usable end-to-end, not just
                    when individual checkboxes are ticked.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-3">
                @foreach ([
                    ['Planned', 'Scoped and queued, but no implementation has started yet.', 'bg-zinc-100 text-zinc-700'],
                    ['In progress', 'Actively being designed, built, or tested in the current cycle.', 'bg-amber-50 text-amber-700'],
                    ['Shipped', 'Live in the app today. Refinements may still land later.', 'bg-emerald-50 text-emerald-700'],
                ] as [$status, $copy, $badge])
                    <div class="rounded-lg border border-zinc-200 bg-white p-5">
                        <span
                            class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">{{ $status }}</span>
                        <p class="mt-3 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                    </div>
                @endforeach
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

    <section class="border-y border-zinc-200 bg-white">
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
                    ['Safe first', 'Privacy, account-scoped data, and reliable storage stay the baseline before any feature is added.'],
                    ['Useful daily', 'Search, filters, reminders, and dashboard signals should reduce manual tracking, not add to it.'],
                    ['Simple to adopt', 'The app should stay usable for one person before adding shared or collaborative workflows.'],
                ] as [$title, $copy])
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
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
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">Not on the roadmap</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Some things stay off the list on purpose. Naming them makes the direction easier to trust, and
                    keeps the product focused on what it is for.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['Public document sharing', 'No public links, no shared inboxes, no "anyone with the link can view" surface.'],
                    ['Advertising or tracking', 'No promotional emails, no behavioural tracking, no third-party analytics layered on top.'],
                    ['Marketplace integrations', 'No integrations with social platforms, ad networks, or e-commerce checkouts.'],
                    ['Forced redesigns', 'No periodic UI overhauls that change muscle memory for the sake of looking new.'],
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
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-6 py-12 sm:px-12 sm:py-14">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-950">Want context on why a phase exists?
                    </h2>
                    <p class="mt-4 text-base leading-7 text-zinc-600">
                        The About page explains what Document Nest is for and how privacy works. The License page
                        covers the source-code terms under GPL-3.0.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('about') }}"
                           class="inline-flex w-full items-center justify-center rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-zinc-800 sm:w-auto">
                            Read about the project
                        </a>
                        <a href="{{ route('license') }}"
                           class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-100 sm:w-auto">
                            Review the license
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
