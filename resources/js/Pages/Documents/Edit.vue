<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
    document: Object,
    categories: Array,
    tags: Array,
    statuses: Array,
})

const form = useForm({
    title: props.document.title ?? '',
    category_id: props.document.category_id ?? '',
    tag_ids: props.document.tag_ids ?? [],
    issue_date: props.document.issue_date ?? '',
    expiry_date: props.document.expiry_date ?? '',
    status: props.document.status ?? 'active',
    notes: props.document.notes ?? '',
})

const submit = () => {
    form.put(`/documents/${props.document.id}`)
}
</script>

<template>
    <Head title="Edit Document" />

    <AppLayout>
        <div class="max-w-3xl space-y-4">
            <h1 class="text-2xl font-semibold">Edit Document</h1>
            <form class="space-y-3" @submit.prevent="submit">
                <input v-model="form.title" placeholder="Title" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <select v-model="form.category_id" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">No category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <select v-model="form.status" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                </select>
                <input v-model="form.issue_date" type="date" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <input v-model="form.expiry_date" type="date" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <select multiple class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" @change="form.tag_ids = Array.from($event.target.selectedOptions).map((option) => option.value)">
                    <option v-for="tag in tags" :key="tag.id" :selected="form.tag_ids.includes(tag.id)" :value="tag.id">{{ tag.name }}</option>
                </select>
                <textarea v-model="form.notes" placeholder="Notes" rows="4" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" />
                <button type="submit" :disabled="form.processing" class="rounded-md bg-zinc-900 px-3 py-2 text-sm font-medium text-white disabled:opacity-50">
                    Update
                </button>
            </form>
        </div>
    </AppLayout>
</template>
