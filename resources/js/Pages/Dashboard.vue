<script setup>
import {Head, Link} from '@inertiajs/vue3'
import {CalendarIcon, DocumentTextIcon, ExclamationCircleIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../Layouts/AppLayout.vue'
import Card from '../Components/UI/Card.vue'

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
        <div class="space-y-8">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500">Overview of your document library and upcoming expirations.</p>
            </header>

            <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Card padding="px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-900">
                            <DocumentTextIcon class="h-6 w-6"/>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500">Total documents</p>
                            <p class="text-3xl font-bold text-zinc-900">{{ stats.total_documents }}</p>
                        </div>
                    </div>
                </Card>

                <Card padding="px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <CalendarIcon class="h-6 w-6"/>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500">Expiring in 30 days</p>
                            <p class="text-3xl font-bold text-zinc-900">{{ stats.expiring_soon_count }}</p>
                        </div>
                    </div>
                </Card>

                <Card padding="px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                            <ExclamationCircleIcon class="h-6 w-6"/>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500">Missing expiry date</p>
                            <p class="text-3xl font-bold text-zinc-900">{{ stats.missing_expiry_count }}</p>
                        </div>
                    </div>
                </Card>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
                <Card padding="p-0" title="Recent uploads">
                    <div class="divide-y divide-zinc-100">
                        <div v-for="document in recent_uploads" :key="document.id"
                             class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ document.title }}</p>
                                <p class="text-xs text-zinc-500">{{ document.updated_at }}</p>
                            </div>
                            <span
                                class="rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-zinc-600">
                                {{ document.status }}
                            </span>
                        </div>
                        <div v-if="recent_uploads.length === 0" class="px-6 py-12 text-center text-sm text-zinc-500">
                            No uploads yet.
                        </div>
                    </div>
                    <template #footer>
                        <Link class="text-xs font-semibold text-zinc-900 hover:underline" href="/documents">View all
                            documents →
                        </Link>
                    </template>
                </Card>

                <Card padding="p-0" title="Expiring soon">
                    <div class="divide-y divide-zinc-100">
                        <div v-for="document in expiring_soon" :key="document.id"
                             class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ document.title }}</p>
                                <p class="text-xs text-zinc-500">Expires on {{ document.expiry_date }}</p>
                            </div>
                            <div class="flex h-2 w-2 rounded-full bg-amber-500"/>
                        </div>
                        <div v-if="expiring_soon.length === 0" class="px-6 py-12 text-center text-sm text-zinc-500">
                            No documents expiring soon.
                        </div>
                    </div>
                </Card>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <Card padding="p-0" title="Missing expiry date">
                    <div class="divide-y divide-zinc-100">
                        <div v-for="document in missing_expiry" :key="document.id"
                             class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50">
                            <p class="text-sm font-semibold text-zinc-900">{{ document.title }}</p>
                            <span class="text-xs text-zinc-500">{{ document.status }}</span>
                        </div>
                        <div v-if="missing_expiry.length === 0" class="px-6 py-12 text-center text-sm text-zinc-500">
                            All documents have expiry dates.
                        </div>
                    </div>
                </Card>

                <Card padding="p-0" title="Documents by category">
                    <div class="divide-y divide-zinc-100">
                        <div v-for="group in documents_by_category" :key="group.category_name"
                             class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50">
                            <p class="text-sm font-semibold text-zinc-900">{{ group.category_name }}</p>
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-100 text-xs font-bold text-zinc-900">
                                {{ group.total }}
                            </span>
                        </div>
                        <div v-if="documents_by_category.length === 0"
                             class="px-6 py-12 text-center text-sm text-zinc-500">
                            No documents yet.
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
