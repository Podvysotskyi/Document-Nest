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
@endsection
