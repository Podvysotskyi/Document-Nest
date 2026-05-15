@extends('layouts.public')

@section('title', 'Sign in - Document Nest')

@section('content')
    <section class="flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid w-full max-w-5xl gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)] lg:items-center">
            <div class="order-2 lg:order-1">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">What happens after sign in
                </p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-zinc-950 sm:text-3xl">
                    Your private document vault, ready in seconds
                </h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Document Nest does not store passwords. Continue with Google and you will land on a dashboard
                    scoped only to your records.
                </p>

                <ol class="mt-8 space-y-5">
                    @foreach ([
                        ['1', 'Continue with Google', 'Google verifies your identity and returns you to the app. No passwords to remember or reset.'],
                        ['2', 'Land on your dashboard', 'See documents expiring soon, records missing expiry dates, and recent uploads at a glance.'],
                        ['3', 'Add your first document', 'Upload a PDF or image, set its category and expiry date, and start building your vault.'],
                    ] as [$number, $title, $copy])
                        <li class="flex gap-4">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white">{{ $number }}</span>
                            <div>
                                <p class="text-sm font-semibold text-zinc-950">{{ $title }}</p>
                                <p class="mt-1 text-sm leading-6 text-zinc-600">{{ $copy }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-8 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-700" fill="none" stroke="currentColor"
                             stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-emerald-900">Private by default</p>
                            <p class="mt-1 text-sm leading-6 text-emerald-900">
                                Your documents are visible only to you. Nothing you upload can be reached without
                                signing in to your own account.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <div class="mx-auto w-full max-w-md">
                    <div class="text-center">
                        <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Secure access</p>
                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">Sign in to Document
                            Nest</h1>
                        <p class="mt-3 text-sm leading-6 text-zinc-600">
                            Use your Google account to access your private document vault.
                        </p>
                    </div>

                    <div class="mt-8 rounded-lg border border-zinc-200 bg-white p-6 shadow-xs sm:p-8">
                        <a href="{{ route('auth.google.redirect') }}"
                           class="inline-flex w-full items-center justify-center gap-3 rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-50">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4"/>
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853"/>
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05"/>
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335"/>
                            </svg>
                            Continue with Google
                        </a>

                        <div class="relative mt-6">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-zinc-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs font-medium uppercase tracking-wider">
                                <span class="bg-white px-3 text-zinc-500">No password required</span>
                            </div>
                        </div>

                        <ul class="mt-6 space-y-3 text-sm leading-6 text-zinc-600">
                            <li class="flex items-start gap-3">
                                <svg class="mt-1 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                                     stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span>Authentication runs through Google, so your password never reaches this
                                    app.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-1 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                                     stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span>Documents, categories, and tags are scoped only to your account.</span>
                            </li>
                        </ul>
                    </div>

                    <p class="mt-6 text-center text-xs leading-5 text-zinc-500">
                        By continuing you agree to keep your documents in a private vault scoped to your account.
                        Source code is available under
                        <a class="font-semibold text-zinc-700 underline-offset-2 hover:underline"
                           href="{{ route('license') }}">GPL-3.0</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
