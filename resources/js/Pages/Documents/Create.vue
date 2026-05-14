<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
    categories: Array,
    tags: Array,
    statuses: Array,
})

const form = useForm({
    file: null,
    title: '',
    category_id: '',
    tag_ids: [],
    issue_date: '',
    expiry_date: '',
    status: 'active',
    notes: '',
})

const submit = () => {
    form.post('/documents')
}
</script>

<template>
    <Head title="Upload Document" />

    <AppLayout>
        <div class="max-w-3xl space-y-4">
            <h1 class="text-2xl font-semibold">Upload Document</h1>
            <form class="space-y-3" @submit.prevent="submit">
                <input type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" @input="form.file = $event.target.files[0]" class="block w-full text-sm">
                <input v-model="form.title" placeholder="Title" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <select v-model="form.category_id" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">No category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <select v-model="form.status" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                    <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                </select>
                <label class="block text-sm text-zinc-600">Issue date</label>
                <input v-model="form.issue_date" type="date" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <label class="block text-sm text-zinc-600">Expiry date</label>
                <input v-model="form.expiry_date" type="date" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <select multiple class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" @change="form.tag_ids = Array.from($event.target.selectedOptions).map((option) => option.value)">
                    <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                </select>
                <textarea v-model="form.notes" placeholder="Notes" rows="4" class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" />
                <button type="submit" :disabled="form.processing" class="rounded-md bg-zinc-900 px-3 py-2 text-sm font-medium text-white disabled:opacity-50">
                    Save
                </button>
            </form>
        </div>
    </AppLayout>
</template>
