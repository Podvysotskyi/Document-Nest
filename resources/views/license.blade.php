@extends('layouts.public')

@section('title', 'License • Document Nest')

@section('content')
    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">License</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-zinc-950 sm:text-5xl">
                    Distributed under the GNU General Public License, Version 3
                </h1>
                <p class="mt-5 text-lg leading-8 text-zinc-600">
                    Document Nest is free software. You can run it, study how it works, share it, and modify it. If you
                    redistribute it — modified or not — your version stays under the same license, and the source has
                    to travel with it.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3 text-xs">
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                        GPL-3.0
                    </span>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 font-semibold text-zinc-700">
                        Copyleft
                    </span>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 font-semibold text-zinc-700">
                        OSI approved
                    </span>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 font-semibold text-zinc-700">
                        FSF endorsed
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-zinc-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">In plain language</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    A short summary of what GPL-3.0 actually allows and requires. This is a quick reference, not legal
                    advice — the full license text below is the authoritative document.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5">
                    <h3 class="text-sm font-semibold text-emerald-900">You can</h3>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-emerald-900">
                        @foreach ([
                            'Run the software for any purpose, personal or commercial',
                            'Study the source code and learn from it',
                            'Modify the source to fit your own needs',
                            'Share the original or your modified version',
                            'Charge a fee for distribution or support',
                        ] as $item)
                            <li class="flex gap-2">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-5">
                    <h3 class="text-sm font-semibold text-amber-900">You must</h3>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-amber-900">
                        @foreach ([
                            'Keep the source code available when you distribute the software',
                            'Release modifications under GPL-3.0 as well',
                            'Preserve copyright notices and license text',
                            'State clearly which changes you made and when',
                            'Pass these same freedoms on to anyone you share with',
                        ] as $item)
                            <li class="flex gap-2">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-amber-600"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-zinc-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">What this means for you</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    The license describes how the source code is shared. It does not change anything about how your
                    documents are stored when you use a Document Nest instance.
                </p>
            </div>
            <div class="grid gap-4 lg:col-span-2 sm:grid-cols-2">
                @foreach ([
                    ['Your data stays yours', 'GPL-3.0 covers the application source code. Documents you upload to a Document Nest instance you control remain on that instance, served only through authorized routes.'],
                    ['Self-host with confidence', 'You can deploy Document Nest on your own infrastructure, study its behavior, and audit how it handles your records without paying for or asking permission to do so.'],
                    ['Fork-friendly', 'If Document Nest does not fit your workflow, you can fork it, adapt it, and run that fork — provided your version stays under GPL-3.0 if you share it.'],
                    ['No warranty', 'The software is provided as-is. There is no warranty unless required by applicable law. Run backups of your documents and review the code before depending on it.'],
                ] as [$title, $copy])
                    <div class="rounded-lg border border-zinc-200 bg-white p-5 ring-1 ring-inset ring-zinc-100">
                        <h3 class="text-sm font-semibold text-zinc-950">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-zinc-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl font-bold tracking-tight text-zinc-950">Full license text</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    The complete GNU General Public License, Version 3, as published by the Free Software Foundation.
                    This is the authoritative text — the summary above is only a quick reference.
                </p>
                <p class="mt-3 text-xs text-zinc-500">
                    Also available at the project root in the
                    <code class="rounded bg-zinc-100 px-1 py-0.5 font-mono text-zinc-800">LICENSE</code> file.
                </p>
            </div>

            <article class="mt-8 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-xs">
                <header class="flex items-center justify-between gap-4 border-b border-zinc-200 px-5 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">LICENSE</p>
                    <p class="text-xs text-zinc-500">GNU GPL v3 • 29 June 2007</p>
                </header>
                <pre
                    class="max-h-[70vh] overflow-auto whitespace-pre-wrap px-5 py-5 font-mono text-xs leading-6 text-zinc-700">{{ $licenseText }}</pre>
            </article>
        </div>
    </section>
@endsection