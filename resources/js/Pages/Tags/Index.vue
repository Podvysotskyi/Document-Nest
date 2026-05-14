<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import {PencilSquareIcon, TagIcon, TrashIcon} from '@heroicons/vue/24/outline'
import {ref} from 'vue'
import AppLayout from '../../Layouts/AppLayout.vue'
import Card from '../../Components/UI/Card.vue'
import Input from '../../Components/UI/Input.vue'
import Button from '../../Components/UI/Button.vue'

defineProps({
    tags: Array,
})

const editingTagId = ref(null)

const createForm = useForm({
    name: '',
})

const editForm = useForm({
    name: '',
})

const submitCreate = () => {
    createForm.post('/tags', {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    })
}

const startEdit = (tag) => {
    editingTagId.value = tag.id
    editForm.defaults({
        name: tag.name,
    })
    editForm.reset()
    editForm.clearErrors()
}

const cancelEdit = () => {
    editingTagId.value = null
    editForm.clearErrors()
}

const submitEdit = (tagId) => {
    editForm.patch(`/tags/${tagId}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingTagId.value = null
        },
    })
}

const destroyTag = (tagId) => {
    if (!window.confirm('Delete this tag?')) {
        return
    }

    editForm.delete(`/tags/${tagId}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Tags"/>

    <AppLayout>
        <div class="space-y-6">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Tags</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage document tags for your account.</p>
            </header>

            <div class="grid gap-6 lg:grid-cols-3">
                <Card class="lg:col-span-1" title="Create Tag">
                    <form class="space-y-4" @submit.prevent="submitCreate">
                        <Input
                            v-model="createForm.name"
                            :error="createForm.errors.name"
                            label="Name"
                            placeholder="e.g. Important"
                            required
                        />

                        <div class="flex justify-end">
                            <Button :disabled="createForm.processing" type="submit">
                                {{ createForm.processing ? 'Creating...' : 'Create' }}
                            </Button>
                        </div>
                    </form>
                </Card>

                <Card class="lg:col-span-2" title="Your Tags">
                    <div class="divide-y divide-zinc-100">
                        <div
                            v-for="tag in tags"
                            :key="tag.id"
                            class="px-4 py-3"
                        >
                            <form
                                v-if="editingTagId === tag.id"
                                class="flex flex-wrap items-end gap-2"
                                @submit.prevent="submitEdit(tag.id)"
                            >
                                <div class="min-w-56 flex-1">
                                    <Input
                                        v-model="editForm.name"
                                        :error="editForm.errors.name"
                                        label="Name"
                                        required
                                    />
                                </div>
                                <Button :disabled="editForm.processing" size="sm" type="submit">
                                    {{ editForm.processing ? 'Saving...' : 'Save' }}
                                </Button>
                                <Button size="sm" type="button" variant="secondary" @click="cancelEdit">Cancel</Button>
                            </form>

                            <div v-else class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-zinc-900">{{ tag.name }}</p>
                                    <p class="text-xs text-zinc-500">{{ tag.documents_count }} documents</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Button
                                        :disabled="editForm.processing"
                                        size="sm"
                                        type="button"
                                        variant="ghost"
                                        @click="startEdit(tag)"
                                    >
                                        <PencilSquareIcon class="h-4 w-4"/>
                                    </Button>
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="ghost"
                                        @click="destroyTag(tag.id)"
                                    >
                                        <TrashIcon class="h-4 w-4"/>
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div v-if="tags.length === 0" class="px-4 py-12 text-center text-sm text-zinc-500">
                            <div class="flex flex-col items-center gap-2">
                                <TagIcon class="h-9 w-9 text-zinc-300"/>
                                <p class="font-semibold text-zinc-700">No tags yet</p>
                                <p class="max-w-sm">Create tags to label documents across categories.</p>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
