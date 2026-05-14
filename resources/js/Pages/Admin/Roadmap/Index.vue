<script setup>
import {computed, reactive, ref} from 'vue'
import {Head, Link, router, useForm} from '@inertiajs/vue3'
import {
    ArrowDownIcon,
    ArrowUpIcon,
    EyeIcon,
    EyeSlashIcon,
    MapIcon,
    PencilSquareIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline'
import AppLayout from '../../../Layouts/AppLayout.vue'
import Badge from '../../../Components/UI/Badge.vue'
import Button from '../../../Components/UI/Button.vue'
import Card from '../../../Components/UI/Card.vue'
import Input from '../../../Components/UI/Input.vue'
import Modal from '../../../Components/UI/Modal.vue'

const props = defineProps({
    phases: Array,
})

const editingPhaseId = ref(null)
const editingItemId = ref(null)
const itemDrafts = reactive({})

const phaseCreateForm = useForm({
    label: '',
    title: '',
    status: '',
    sort_order: props.phases.length + 1,
    is_visible: true,
})

const phaseEditForm = useForm({
    label: '',
    title: '',
    status: '',
    sort_order: 0,
    is_visible: true,
})

const itemEditForm = useForm({
    title: '',
    sort_order: 0,
    is_visible: true,
})

const partialReloadOptions = {
    only: ['phases'],
    preserveState: true,
    preserveScroll: true,
}

const createPhase = () => {
    phaseCreateForm.sort_order = props.phases.length + 1
    phaseCreateForm.post('/admin/roadmap/phases', {
        ...partialReloadOptions,
        onSuccess: () => {
            phaseCreateForm.reset()
            phaseCreateForm.sort_order = props.phases.length + 1
            phaseCreateForm.is_visible = true
        },
    })
}

const startPhaseEdit = (phase) => {
    editingPhaseId.value = phase.id
    phaseEditForm.defaults({
        label: phase.label,
        title: phase.title,
        status: phase.status,
        sort_order: phase.sort_order,
        is_visible: phase.is_visible,
    })
    phaseEditForm.reset()
    phaseEditForm.clearErrors()
}

const editingPhase = computed(() => props.phases.find((phase) => phase.id === editingPhaseId.value) ?? null)

const closePhaseEdit = () => {
    editingPhaseId.value = null
    phaseEditForm.clearErrors()
}

const updatePhase = (phase) => {
    phaseEditForm.patch(`/admin/roadmap/phases/${phase.id}`, {
        ...partialReloadOptions,
        onSuccess: () => closePhaseEdit(),
    })
}

const destroyPhase = (phase) => {
    if (!window.confirm('Delete this roadmap phase and its items?')) {
        return
    }

    router.delete(`/admin/roadmap/phases/${phase.id}`, partialReloadOptions)
}

const movePhase = (phase, direction) => {
    router.post(`/admin/roadmap/phases/${phase.id}/move`, {direction}, partialReloadOptions)
}

const itemDraft = (phase) => {
    if (!itemDrafts[phase.id]) {
        itemDrafts[phase.id] = {
            title: '',
            sort_order: phase.items.length + 1,
            is_visible: true,
        }
    }

    return itemDrafts[phase.id]
}

const createItem = (phase) => {
    const draft = itemDraft(phase)
    draft.sort_order = phase.items.length + 1

    router.post(`/admin/roadmap/phases/${phase.id}/items`, draft, {
        ...partialReloadOptions,
        onSuccess: () => {
            itemDrafts[phase.id] = {
                title: '',
                sort_order: phase.items.length + 1,
                is_visible: true,
            }
        },
    })
}

const startItemEdit = (item) => {
    editingItemId.value = item.id
    itemEditForm.defaults({
        title: item.title,
        sort_order: item.sort_order,
        is_visible: item.is_visible,
    })
    itemEditForm.reset()
    itemEditForm.clearErrors()
}

const editingItem = computed(() => {
    if (editingItemId.value === null) return null
    for (const phase of props.phases) {
        const found = phase.items.find((item) => item.id === editingItemId.value)
        if (found) return found
    }
    return null
})

const closeItemEdit = () => {
    editingItemId.value = null
    itemEditForm.clearErrors()
}

const updateItem = (item) => {
    itemEditForm.patch(`/admin/roadmap/items/${item.id}`, {
        ...partialReloadOptions,
        onSuccess: () => closeItemEdit(),
    })
}

const destroyItem = (item) => {
    if (!window.confirm('Delete this roadmap item?')) {
        return
    }

    router.delete(`/admin/roadmap/items/${item.id}`, partialReloadOptions)
}

const moveItem = (item, direction) => {
    router.post(`/admin/roadmap/items/${item.id}/move`, {direction}, partialReloadOptions)
}
</script>

<template>
    <Head title="Admin Roadmap"/>

    <AppLayout>
        <div class="space-y-6">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <Link class="text-sm font-medium text-zinc-500 hover:text-zinc-900" href="/admin">Admin</Link>
                        <span class="text-sm text-zinc-300">/</span>
                        <span class="text-sm font-medium text-zinc-900">Roadmap</span>
                    </div>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-900">Roadmap</h1>
                    <p class="mt-1 text-sm text-zinc-500">Manage phases and items on the public roadmap page.</p>
                </div>
                <Button href="/roadmap" rel="noopener" target="_blank" variant="secondary">
                    <MapIcon class="mr-2 h-4 w-4"/>
                    Public roadmap
                </Button>
            </header>

            <Card title="Create Phase">
                <form class="grid gap-4 lg:grid-cols-12" @submit.prevent="createPhase">
                    <Input v-model="phaseCreateForm.label" :error="phaseCreateForm.errors.label" class="lg:col-span-2" label="Label" required/>
                    <Input v-model="phaseCreateForm.title" :error="phaseCreateForm.errors.title" class="lg:col-span-5" label="Title" required/>
                    <Input v-model="phaseCreateForm.status" :error="phaseCreateForm.errors.status" class="lg:col-span-3" label="Status" required/>
                    <label class="flex items-end gap-2 text-sm font-medium text-zinc-700 lg:col-span-1">
                        <input v-model="phaseCreateForm.is_visible" class="mb-2 h-4 w-4 rounded border-zinc-300 text-zinc-900" type="checkbox">
                        Visible
                    </label>
                    <div class="flex items-end lg:col-span-1">
                        <Button :disabled="phaseCreateForm.processing" class="w-full" type="submit">Create</Button>
                    </div>
                </form>
            </Card>

            <Card padding="p-0" title="Phases">
                <div class="divide-y divide-zinc-100">
                    <section v-for="(phase, phaseIndex) in phases" :key="phase.id" class="space-y-4 px-4 py-5">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <Badge>{{ phase.label }}</Badge>
                                    <Badge :variant="phase.is_visible ? 'success' : 'neutral'">
                                        <EyeIcon v-if="phase.is_visible" class="mr-1 h-3 w-3"/>
                                        <EyeSlashIcon v-else class="mr-1 h-3 w-3"/>
                                        {{ phase.is_visible ? 'Visible' : 'Hidden' }}
                                    </Badge>
                                </div>
                                <h2 class="mt-2 text-lg font-semibold text-zinc-900">{{ phase.title }}</h2>
                                <p class="text-sm text-zinc-500">{{ phase.status }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <Button :disabled="phaseIndex === 0" aria-label="Move phase up" size="sm" type="button" variant="ghost" @click="movePhase(phase, 'up')">
                                    <ArrowUpIcon class="h-4 w-4"/>
                                </Button>
                                <Button :disabled="phaseIndex === phases.length - 1" aria-label="Move phase down" size="sm" type="button" variant="ghost" @click="movePhase(phase, 'down')">
                                    <ArrowDownIcon class="h-4 w-4"/>
                                </Button>
                                <Button size="sm" type="button" variant="ghost" @click="startPhaseEdit(phase)">
                                    <PencilSquareIcon class="h-4 w-4"/>
                                </Button>
                                <Button size="sm" type="button" variant="ghost" @click="destroyPhase(phase)">
                                    <TrashIcon class="h-4 w-4"/>
                                </Button>
                            </div>
                        </div>

                        <div class="rounded-lg border border-zinc-200">
                            <div class="divide-y divide-zinc-100">
                                <div v-for="(item, itemIndex) in phase.items" :key="item.id" class="px-3 py-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-zinc-900">{{ item.title }}</p>
                                            <p class="text-xs text-zinc-500">
                                                {{ item.is_visible ? 'Visible' : 'Hidden' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <Button :disabled="itemIndex === 0" aria-label="Move item up" size="sm" type="button" variant="ghost" @click="moveItem(item, 'up')">
                                                <ArrowUpIcon class="h-4 w-4"/>
                                            </Button>
                                            <Button :disabled="itemIndex === phase.items.length - 1" aria-label="Move item down" size="sm" type="button" variant="ghost" @click="moveItem(item, 'down')">
                                                <ArrowDownIcon class="h-4 w-4"/>
                                            </Button>
                                            <Button size="sm" type="button" variant="ghost" @click="startItemEdit(item)">
                                                <PencilSquareIcon class="h-4 w-4"/>
                                            </Button>
                                            <Button size="sm" type="button" variant="ghost" @click="destroyItem(item)">
                                                <TrashIcon class="h-4 w-4"/>
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="grid gap-3 border-t border-zinc-200 bg-zinc-50/50 px-3 py-3 md:grid-cols-12" @submit.prevent="createItem(phase)">
                                <Input v-model="itemDraft(phase).title" class="md:col-span-9" label="New item" required/>
                                <label class="flex items-end gap-2 text-sm font-medium text-zinc-700 md:col-span-1">
                                    <input v-model="itemDraft(phase).is_visible" class="mb-2 h-4 w-4 rounded border-zinc-300 text-zinc-900" type="checkbox">
                                    Visible
                                </label>
                                <div class="flex items-end md:col-span-2">
                                    <Button class="w-full" size="sm" type="submit" variant="secondary">Add item</Button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <div v-if="phases.length === 0" class="px-4 py-12 text-center text-sm text-zinc-500">
                        <MapIcon class="mx-auto h-9 w-9 text-zinc-300"/>
                        <p class="mt-2 font-semibold text-zinc-700">No roadmap phases</p>
                    </div>
                </div>
            </Card>
        </div>

        <Modal :open="editingPhase !== null" title="Edit phase" @close="closePhaseEdit">
            <form v-if="editingPhase" class="space-y-4" @submit.prevent="updatePhase(editingPhase)">
                <Input v-model="phaseEditForm.label" :error="phaseEditForm.errors.label" label="Label" required/>
                <Input v-model="phaseEditForm.title" :error="phaseEditForm.errors.title" label="Title" required/>
                <Input v-model="phaseEditForm.status" :error="phaseEditForm.errors.status" label="Status" required/>
                <label class="flex items-center gap-2 text-sm font-medium text-zinc-700">
                    <input v-model="phaseEditForm.is_visible" class="h-4 w-4 rounded border-zinc-300 text-zinc-900" type="checkbox">
                    Visible on public roadmap
                </label>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <Button type="button" variant="secondary" @click="closePhaseEdit">Cancel</Button>
                    <Button :disabled="phaseEditForm.processing" type="submit">Save</Button>
                </div>
            </form>
        </Modal>

        <Modal :open="editingItem !== null" title="Edit item" @close="closeItemEdit">
            <form v-if="editingItem" class="space-y-4" @submit.prevent="updateItem(editingItem)">
                <Input v-model="itemEditForm.title" :error="itemEditForm.errors.title" label="Title" required/>
                <label class="flex items-center gap-2 text-sm font-medium text-zinc-700">
                    <input v-model="itemEditForm.is_visible" class="h-4 w-4 rounded border-zinc-300 text-zinc-900" type="checkbox">
                    Visible on public roadmap
                </label>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <Button type="button" variant="secondary" @click="closeItemEdit">Cancel</Button>
                    <Button :disabled="itemEditForm.processing" type="submit">Save</Button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
