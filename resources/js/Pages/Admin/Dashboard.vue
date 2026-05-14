<script setup>
import {Head, Link} from '@inertiajs/vue3'
import {MapIcon, UsersIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../../Layouts/AppLayout.vue'
import Card from '../../Components/UI/Card.vue'

defineProps({
    canManageRoadmap: {
        type: Boolean,
        required: true,
    },
})
</script>

<template>
    <Head title="Admin"/>

    <AppLayout>
        <div class="space-y-6">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Admin</h1>
                <p class="mt-1 text-sm text-zinc-500">
                    {{
                        canManageRoadmap ? 'Manage users and public roadmap content.' : 'Manage users and account access.'
                    }}
                </p>
            </header>

            <div class="grid gap-4 md:grid-cols-2">
                <Card title="Users" subtitle="Manage account access and admin permissions.">
                    <Link
                        class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 shadow-xs transition-colors hover:bg-zinc-50 hover:text-zinc-900"
                        href="/admin/users"
                    >
                        <UsersIcon class="h-4 w-4"/>
                        Open users
                    </Link>
                </Card>

                <Card v-if="canManageRoadmap" subtitle="Edit the content shown on the public roadmap page."
                      title="Roadmap">
                    <Link
                        class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 shadow-xs transition-colors hover:bg-zinc-50 hover:text-zinc-900"
                        href="/admin/roadmap"
                    >
                        <MapIcon class="h-4 w-4"/>
                        Open roadmap
                    </Link>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
