<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {DocumentTextIcon} from '@heroicons/vue/24/outline'

const page = usePage()
</script>

<template>
    <div class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased">
        <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-8">
                    <a class="flex items-center gap-2 font-bold tracking-tight text-zinc-900" href="/">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white">
                            <DocumentTextIcon class="h-5 w-5"/>
                        </div>
                        <span>Document Nest</span>
                    </a>

                    <nav class="hidden items-center gap-1 md:flex">
                        <Link
                            :class="[
                                'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                $page.component === 'Dashboard' ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900'
                            ]"
                            href="/dashboard"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :class="[
                                'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                $page.component.startsWith('Documents/') ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900'
                            ]"
                            href="/documents"
                        >
                            Documents
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden items-center gap-3 border-r border-zinc-200 pr-4 sm:flex">
                        <div class="text-right">
                            <div class="text-sm font-medium text-zinc-900">{{ $page.props.auth.user?.name }}</div>
                            <div class="text-xs text-zinc-500">{{ $page.props.auth.user?.email }}</div>
                        </div>
                        <img
                            v-if="$page.props.auth.user?.avatar_url"
                            :src="$page.props.auth.user.avatar_url"
                            alt="User avatar"
                            class="h-9 w-9 rounded-full border border-zinc-200 object-cover shadow-xs"
                        >
                        <div v-else
                             class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-600 border border-zinc-200">
                            {{ $page.props.auth.user?.name?.charAt(0) }}
                        </div>
                    </div>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-xs transition-all hover:bg-zinc-50 hover:text-zinc-900 active:scale-95"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
