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
                <a href="{{ route('roadmap') }}"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 sm:inline-flex">
                    Roadmap
                </a>
            </nav>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Secure access</p>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl">Sign in to Document Nest</h1>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Use your Google account to access your private document vault.
                </p>
            </div>

            <div class="mt-8 rounded-lg border border-zinc-200 bg-white p-6 shadow-xs sm:p-8">
                <a href="{{ route('auth.google.redirect') }}"
                   class="inline-flex w-full items-center justify-center gap-3 rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-50">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                              fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                              fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                              fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
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

                <p class="mt-6 text-center text-sm leading-6 text-zinc-600">
                    Don't have an account?
                    <span class="font-semibold text-zinc-900">Google will create one for you.</span>
                </p>
            </div>

            <p class="mt-6 text-center text-xs text-zinc-500">
                By continuing you agree to keep your documents in a private vault scoped to your account.
            </p>
        </div>
    </main>

    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>Document Nest &copy; {{ date('Y') }}</p>
            <p>Google-only sign in. Private local storage for v1.</p>
        </div>
    </footer>
</div>
</body>
</html>
