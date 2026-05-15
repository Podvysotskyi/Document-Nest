@extends('layouts.public')

@section('title', 'License • Document Nest')

@section('content')
    <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-zinc-950">License</h1>
        <p class="mt-4 text-base leading-7 text-zinc-700">
            Document Nest is distributed under the GNU General Public License, Version 3 (GPLv3).
        </p>
        <p class="mt-3 text-sm leading-6 text-zinc-600">
            The full license text is included in the repository root file named <code
                class="rounded bg-zinc-100 px-1 py-0.5 text-zinc-800">LICENSE</code>.
        </p>

        <div class="mt-8 rounded-lg border border-zinc-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-zinc-950">Summary</h2>
            <ul class="mt-3 space-y-2 text-sm leading-6 text-zinc-600">
                <li>You may run, study, share, and modify the software.</li>
                <li>If you distribute modified versions, they must remain under GPLv3.</li>
                <li>There is no warranty unless required by law.</li>
            </ul>
        </div>

        <section class="mt-8 rounded-lg border border-zinc-200 bg-white">
            <div class="border-b border-zinc-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-zinc-950">Full license text</h2>
            </div>
            <pre
                class="max-h-[70vh] overflow-auto whitespace-pre-wrap px-5 py-5 text-xs leading-6 text-zinc-700">{{ $licenseText }}</pre>
        </section>
    </div>
@endsection
