<script setup>
import {Head, usePage} from '@inertiajs/vue3'
import {ShieldCheckIcon, UserCircleIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../Layouts/AppLayout.vue'
import Card from '../Components/UI/Card.vue'

const page = usePage()
</script>

<template>
    <Head title="Profile"/>

    <AppLayout>
        <div class="space-y-8">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Profile</h1>
                <p class="mt-1 text-sm text-zinc-500">Your account details for Document Nest.</p>
            </header>

            <Card padding="p-0">
                <div class="flex flex-col gap-6 px-6 py-6 sm:flex-row sm:items-center">
                    <img
                        v-if="page.props.auth.user?.avatar_url"
                        :src="page.props.auth.user.avatar_url"
                        alt="User avatar"
                        class="h-20 w-20 rounded-full border border-zinc-200 object-cover shadow-xs"
                    >
                    <div
                        v-else
                        class="flex h-20 w-20 items-center justify-center rounded-full border border-zinc-200 bg-zinc-100 text-2xl font-semibold text-zinc-600"
                    >
                        {{ page.props.auth.user?.name?.charAt(0) }}
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-semibold text-zinc-900">{{ page.props.auth.user?.name }}</h2>
                            <span
                                v-if="page.props.auth.user?.is_admin"
                                class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700"
                            >
                                <ShieldCheckIcon class="h-3.5 w-3.5"/>
                                Admin
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">{{ page.props.auth.user?.email }}</p>
                    </div>
                </div>

                <div class="border-t border-zinc-200 px-6 py-5">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-zinc-500">Name</dt>
                            <dd class="mt-1 text-sm font-medium text-zinc-900">{{ page.props.auth.user?.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-zinc-500">Email</dt>
                            <dd class="mt-1 text-sm font-medium text-zinc-900">{{ page.props.auth.user?.email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-zinc-500">Roles</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                <span
                                    v-for="role in page.props.auth.user?.roles"
                                    :key="role"
                                    class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-700"
                                >
                                    {{ role }}
                                </span>
                                <span
                                    v-if="page.props.auth.user?.roles.length === 0"
                                    class="inline-flex items-center gap-1 text-sm text-zinc-500"
                                >
                                    <UserCircleIcon class="h-4 w-4"/>
                                    Standard user
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
