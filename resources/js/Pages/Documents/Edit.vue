<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import Button from '../../Components/UI/Button.vue'
import Input from '../../Components/UI/Input.vue'
import Card from '../../Components/UI/Card.vue'
import Select from '../../Components/UI/Select.vue'
import Textarea from '../../Components/UI/Textarea.vue'

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
        <div class="mx-auto max-w-3xl space-y-6">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Edit Document</h1>
                <p class="mt-1 text-sm text-zinc-500">Update the details for "{{ document.title }}".</p>
            </header>

            <form class="space-y-6" @submit.prevent="submit">
                <Card title="General Information">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <Input v-model="form.title" :error="form.errors.title" label="Title"
                                   placeholder="e.g. Identity Card" required/>
                        </div>

                        <Select
                            v-model="form.category_id"
                            :error="form.errors.category_id"
                            :options="categories.map(c => ({ value: c.id, label: c.name }))"
                            label="Category"
                            placeholder="No category"
                        />

                        <Select
                            v-model="form.status"
                            :error="form.errors.status"
                            :options="statuses.map(s => ({ value: s, label: s.charAt(0).toUpperCase() + s.slice(1) }))"
                            label="Status"
                            required
                        />

                        <Input v-model="form.issue_date" :error="form.errors.issue_date" label="Issue Date"
                               type="date"/>
                        <Input v-model="form.expiry_date" :error="form.errors.expiry_date" label="Expiry Date"
                               type="date"/>

                        <div class="sm:col-span-2">
                            <Textarea v-model="form.notes" :error="form.errors.notes" label="Notes"
                                      placeholder="Any additional details..."/>
                        </div>
                    </div>
                </Card>

                <div class="flex items-center justify-end gap-3">
                    <Button :href="`/documents/${document.id}`" variant="secondary">Cancel</Button>
                    <Button :disabled="form.processing" type="submit">
                        {{ form.processing ? 'Saving...' : 'Update Document' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
