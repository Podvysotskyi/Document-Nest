@extends('layouts.public')

@section('title', 'About • Document Nest')

@section('content')
    <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-zinc-950">About Document Nest</h1>
        <p class="mt-4 text-base leading-7 text-zinc-700">
            Document Nest is a private document organizer focused on personal records. It helps you store files,
            categorize them, add expiry dates and notes, and quickly retrieve what matters.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-zinc-950">Privacy first</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">
                    Uploaded files stay in private local storage and are served through authorized routes.
                </p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-zinc-950">Built for lifecycle work</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">
                    Track issue and expiry dates, archive outdated records, and keep important documents actionable.
                </p>
            </div>
        </div>
    </div>
@endsection
