<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sign in - Document Nest</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    @vite('resources/css/app.css')
</head>
<body class="min-h-full font-sans text-zinc-900 antialiased">
<div class="flex min-h-screen flex-col bg-zinc-50">
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
                <a href="{{ route('about') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 sm:inline-flex">
                    About
                </a>
                <a href="{{ route('roadmap') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 sm:inline-flex">
                    Roadmap
                </a>
            </nav>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
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
    </main>

    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-3">
                <p>Document Nest &copy; {{ date('Y') }}</p>
                <span class="hidden text-zinc-300 sm:inline">&middot;</span>
                <p>
                    Made by
                    <a class="font-medium text-zinc-600 transition-colors hover:text-zinc-900"
                       href="https://www.linkedin.com/in/podvysotskyi/"
                       target="_blank" rel="noopener noreferrer">Serhii Podvysotskyi</a>
                </p>
            </div>
            <nav class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <a class="inline-flex items-center gap-1.5 font-medium text-zinc-600 transition-colors hover:text-zinc-900"
                   href="https://github.com/Podvysotskyi/Document-Nest"
                   target="_blank" rel="noopener noreferrer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z"/>
                    </svg>
                    GitHub
                </a>
                <a class="inline-flex items-center gap-1.5 font-medium text-zinc-600 transition-colors hover:text-zinc-900"
                   href="https://www.linkedin.com/in/podvysotskyi/"
                   target="_blank" rel="noopener noreferrer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.852 3.37-1.852 3.602 0 4.268 2.37 4.268 5.455v6.288zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.063 2.063 0 1 1 2.063 2.065zm1.78 13.019H3.555V9h3.562v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    LinkedIn
                </a>
            </nav>
        </div>
    </footer>
</div>
</body>
</html>
