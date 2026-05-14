<script setup>
import {Link, usePage} from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <div class="min-h-screen bg-zinc-50 text-zinc-900">
        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex h-14 w-full max-w-6xl items-center justify-between px-4">
                <a class="font-semibold" href="/">
                    Document Nest
                </a>

                <nav class="flex items-center gap-2 text-sm">
                    <Link class="rounded-md px-2 py-1 hover:bg-zinc-100" href="/dashboard">Dashboard</Link>
                    <Link href="/documents" class="rounded-md px-2 py-1 hover:bg-zinc-100">Documents</Link>
                </nav>

                <div class="flex items-center gap-3">
                    <div class="text-sm text-zinc-600">
                        {{ page.props.auth.user?.name }}
                    </div>
                    <img
                        v-if="page.props.auth.user?.avatar_url"
                        :src="page.props.auth.user.avatar_url"
                        alt="User avatar"
                        class="h-8 w-8 rounded-full"
                    >
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium hover:bg-zinc-100"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-6">
            <slot />
        </main>
    </div>
</template>
