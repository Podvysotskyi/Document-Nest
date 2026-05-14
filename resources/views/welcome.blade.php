<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Document Nest</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="mt-10 text-center text-3xl font-bold tracking-tight text-indigo-600">Document Nest</h1>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-gray-900">Securely organize your important
            life documents</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="space-y-6">
            <div class="grid gap-6">
                <div class="flex flex-col items-center gap-4 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A4.877 4.877 0 0 0 12 9c-1.352 0-2.587.55-3.484 1.482M18 21h3m-15 0h-3"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold text-gray-900">Organize Your Life</h3>
                        <p class="mt-2 text-sm text-gray-500">Keep all your important documents in one secure place.
                            From IDs to financial statements.</p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold text-gray-900">Expiry Alerts</h3>
                        <p class="mt-2 text-sm text-gray-500">Never miss an expiration date again. Get notified before
                            your passport or license expires.</p>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sign in to get started
                    </a>
                @endauth
            </div>

            <p class="mt-10 text-center text-sm text-gray-500">
                Document Nest &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</div>
</body>
</html>
