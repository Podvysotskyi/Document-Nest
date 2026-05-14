<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import {CloudArrowUpIcon, XMarkIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../../Layouts/AppLayout.vue'
import Button from '../../Components/UI/Button.vue'
import Input from '../../Components/UI/Input.vue'
import Card from '../../Components/UI/Card.vue'
import Select from '../../Components/UI/Select.vue'
import Textarea from '../../Components/UI/Textarea.vue'

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
        <div class="mx-auto max-w-3xl space-y-6">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Upload Document</h1>
                <p class="mt-1 text-sm text-zinc-500">Add a new document to your library. PDF, JPG, PNG, and WebP are
                    supported.</p>
            </header>

            <form class="space-y-6" @submit.prevent="submit">
                <Card title="Document File">
                    <div class="space-y-4">
                        <div
                            class="flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-200 bg-zinc-50/50 p-6 transition-colors hover:border-zinc-300">
                            <div class="text-center">
                                <CloudArrowUpIcon class="mx-auto h-10 w-10 text-zinc-400"/>
                                <div class="mt-4 flex text-sm text-zinc-600">
                                    <label
                                        class="relative cursor-pointer rounded-md font-semibold text-zinc-900 hover:text-zinc-700">
                                        <span>Select a file</span>
                                        <input accept=".pdf,.jpg,.jpeg,.png,.webp" class="sr-only"
                                               type="file" @input="form.file = $event.target.files[0]">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-zinc-500">PDF, PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                        <div v-if="form.file"
                             class="flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 text-sm text-zinc-700">
                            <span class="truncate font-medium">{{ form.file.name }}</span>
                            <button class="text-zinc-400 hover:text-zinc-600" type="button" @click="form.file = null">
                                <XMarkIcon class="h-5 w-5"/>
                            </button>
                        </div>
                        <p v-if="form.errors.file" class="text-sm text-red-600">{{ form.errors.file }}</p>
                    </div>
                </Card>

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

                <Card title="Tags">
                    <div v-if="tags.length" class="grid gap-2 sm:grid-cols-2">
                        <label
                            v-for="tag in tags"
                            :key="tag.id"
                            class="flex min-h-11 cursor-pointer items-center gap-3 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm transition-colors hover:bg-zinc-50"
                        >
                            <input
                                v-model="form.tag_ids"
                                :value="tag.id"
                                class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900"
                                type="checkbox"
                            >
                            <span class="min-w-0 truncate font-medium text-zinc-800">{{ tag.name }}</span>
                        </label>
                    </div>
                    <p v-else class="text-sm text-zinc-500">No tags yet.</p>
                    <p v-if="form.errors.tag_ids" class="mt-2 text-sm text-red-600">{{ form.errors.tag_ids }}</p>
                    <p v-if="form.errors['tag_ids.0']" class="mt-2 text-sm text-red-600">
                        {{ form.errors['tag_ids.0'] }}
                    </p>
                </Card>

                <div class="flex items-center justify-end gap-3">
                    <Button href="/documents" variant="secondary">Cancel</Button>
                    <Button :disabled="form.processing" type="submit">
                        {{ form.processing ? 'Uploading...' : 'Save Document' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
