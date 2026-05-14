<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import {PencilSquareIcon, TrashIcon} from '@heroicons/vue/24/outline'
import {ref} from 'vue'
import AppLayout from '../../Layouts/AppLayout.vue'
import Card from '../../Components/UI/Card.vue'
import Input from '../../Components/UI/Input.vue'
import Button from '../../Components/UI/Button.vue'

const props = defineProps({
    categories: Array,
})

const editingCategoryId = ref(null)

const createForm = useForm({
    name: '',
})

const editForm = useForm({
    name: '',
})

const submitCreate = () => {
    createForm.post('/categories', {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    })
}

const startEdit = (category) => {
    editingCategoryId.value = category.id
    editForm.defaults({
        name: category.name,
    })
    editForm.reset()
    editForm.clearErrors()
}

const cancelEdit = () => {
    editingCategoryId.value = null
    editForm.clearErrors()
}

const submitEdit = (categoryId) => {
    editForm.patch(`/categories/${categoryId}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingCategoryId.value = null
        },
    })
}

const destroyCategory = (categoryId, documentsCount) => {
    if (documentsCount > 0) {
        return
    }

    if (!window.confirm('Delete this category?')) {
        return
    }

    editForm.delete(`/categories/${categoryId}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Categories"/>

    <AppLayout>
        <div class="space-y-6">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Categories</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage document categories for your account.</p>
            </header>

            <div class="grid gap-6 lg:grid-cols-3">
                <Card class="lg:col-span-1" title="Create Category">
                    <form class="space-y-4" @submit.prevent="submitCreate">
                        <Input
                            v-model="createForm.name"
                            :error="createForm.errors.name"
                            label="Name"
                            placeholder="e.g. Insurance"
                            required
                        />

                        <div class="flex justify-end">
                            <Button :disabled="createForm.processing" type="submit">
                                {{ createForm.processing ? 'Creating...' : 'Create' }}
                            </Button>
                        </div>
                    </form>
                </Card>

                <Card class="lg:col-span-2" title="Your Categories">
                    <div class="divide-y divide-zinc-100">
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="px-4 py-3"
                        >
                            <form
                                v-if="editingCategoryId === category.id"
                                class="flex flex-wrap items-end gap-2"
                                @submit.prevent="submitEdit(category.id)"
                            >
                                <div class="min-w-56 flex-1">
                                    <Input
                                        v-model="editForm.name"
                                        :error="editForm.errors.name"
                                        label="Name"
                                        required
                                    />
                                </div>
                                <Button :disabled="editForm.processing" size="sm" type="submit">Save</Button>
                                <Button size="sm" type="button" variant="secondary" @click="cancelEdit">Cancel</Button>
                            </form>

                            <div v-else class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-zinc-900">{{ category.name }}</p>
                                    <p class="text-xs text-zinc-500">{{ category.documents_count }} documents</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="ghost"
                                        @click="startEdit(category)"
                                    >
                                        <PencilSquareIcon class="h-4 w-4"/>
                                    </Button>
                                    <Button
                                        :disabled="category.documents_count > 0"
                                        :title="category.documents_count > 0 ? 'Cannot delete category with documents' : 'Delete category'"
                                        size="sm"
                                        type="button"
                                        variant="ghost"
                                        @click="destroyCategory(category.id, category.documents_count)"
                                    >
                                        <TrashIcon class="h-4 w-4"/>
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div v-if="categories.length === 0" class="px-4 py-10 text-center text-sm text-zinc-500">
                            No categories yet.
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
