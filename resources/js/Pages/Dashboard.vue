<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'

defineProps({
    stats: Object,
    recent_uploads: Array,
    expiring_soon: Array,
    missing_expiry: Array,
    documents_by_category: Array,
})
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="space-y-6">
            <section class="space-y-2">
                <h1 class="text-2xl font-semibold">Dashboard</h1>
            </section>

            <section class="grid gap-4 md:grid-cols-3">
                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <p class="text-sm text-zinc-600">Total documents</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.total_documents }}</p>
                </div>
                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <p class="text-sm text-zinc-600">Expiring in 30 days</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.expiring_soon_count }}</p>
                </div>
                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <p class="text-sm text-zinc-600">Missing expiry date</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.missing_expiry_count }}</p>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <h2 class="text-base font-semibold">Recent uploads</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li v-for="document in recent_uploads" :key="document.id" class="flex items-center justify-between">
                            <span>{{ document.title }}</span>
                            <span class="text-zinc-500">{{ document.status }}</span>
                        </li>
                        <li v-if="recent_uploads.length === 0" class="text-zinc-500">No uploads yet.</li>
                    </ul>
                </div>

                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <h2 class="text-base font-semibold">Expiring soon</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li v-for="document in expiring_soon" :key="document.id" class="flex items-center justify-between">
                            <span>{{ document.title }}</span>
                            <span class="text-zinc-500">{{ document.expiry_date }}</span>
                        </li>
                        <li v-if="expiring_soon.length === 0" class="text-zinc-500">No documents expiring soon.</li>
                    </ul>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <h2 class="text-base font-semibold">Missing expiry date</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li v-for="document in missing_expiry" :key="document.id" class="flex items-center justify-between">
                            <span>{{ document.title }}</span>
                            <span class="text-zinc-500">{{ document.status }}</span>
                        </li>
                        <li v-if="missing_expiry.length === 0" class="text-zinc-500">All documents have expiry dates.</li>
                    </ul>
                </div>

                <div class="rounded-md border border-zinc-200 bg-white p-4">
                    <h2 class="text-base font-semibold">Documents by category</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li v-for="group in documents_by_category" :key="group.category_name" class="flex items-center justify-between">
                            <span>{{ group.category_name }}</span>
                            <span class="font-medium">{{ group.total }}</span>
                        </li>
                        <li v-if="documents_by_category.length === 0" class="text-zinc-500">No documents yet.</li>
                    </ul>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
