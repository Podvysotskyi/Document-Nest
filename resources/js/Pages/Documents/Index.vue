<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
    documents: Object,
    filters: Object,
    categories: Array,
    tags: Array,
})

const applyFilters = (event) => {
    const form = new FormData(event.target)

    router.get('/documents', Object.fromEntries(form.entries()), {
        preserveState: true,
        replace: true,
    })
}
</script>

<template>
    <Head title="Documents" />

    <AppLayout>
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Documents</h1>
                <Link href="/documents/create" class="rounded-md bg-zinc-900 px-3 py-2 text-sm font-medium text-white">
                    Upload
                </Link>
            </div>

            <form class="grid gap-2 md:grid-cols-4" @submit.prevent="applyFilters">
                <input name="q" :value="filters.q" placeholder="Search title, notes, filename" class="rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <select name="category_id" :value="filters.category_id" class="rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">All categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <select name="status" :value="filters.status" class="rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">All statuses</option>
                    <option value="active">active</option>
                    <option value="expired">expired</option>
                    <option value="archived">archived</option>
                </select>
                <select name="sort" :value="filters.sort" class="rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="title">Title</option>
                    <option value="expiry_date">Expiry date</option>
                </select>
                <button type="submit" class="w-fit rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100">Apply</button>
            </form>

            <div class="overflow-hidden rounded-md border border-zinc-200 bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-zinc-50 text-left text-zinc-600">
                        <tr>
                            <th class="px-3 py-2">Title</th>
                            <th class="px-3 py-2">Category</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Expiry</th>
                            <th class="px-3 py-2">Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="document in documents.data" :key="document.id" class="border-t border-zinc-100">
                            <td class="px-3 py-2">
                                <Link :href="`/documents/${document.id}`" class="font-medium hover:underline">{{ document.title }}</Link>
                            </td>
                            <td class="px-3 py-2">{{ document.category?.name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ document.status }}</td>
                            <td class="px-3 py-2">{{ document.expiry_date ?? '—' }}</td>
                            <td class="px-3 py-2">{{ document.updated_at }}</td>
                        </tr>
                        <tr v-if="documents.data.length === 0">
                            <td class="px-3 py-8 text-zinc-500" colspan="5">No documents found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
