<script setup>
import {computed, onBeforeUnmount, ref, watch} from 'vue'
import {Deferred, Head, router} from '@inertiajs/vue3'
import {
    ArchiveBoxIcon,
    ArrowDownTrayIcon,
    ArrowPathIcon,
    EyeIcon,
    PencilIcon,
    TrashIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import AppLayout from '../../Layouts/AppLayout.vue'
import Button from '../../Components/UI/Button.vue'
import Badge from '../../Components/UI/Badge.vue'
import Card from '../../Components/UI/Card.vue'

const props = defineProps({
    document: Object,
    previewUrl: String,
    downloadUrl: String,
    preview: Object,
    activities: Object,
})

const dateFormatter = typeof Intl !== 'undefined'
    ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' })
    : null

const formatTimestamp = (value) => {
    if (!value) {
        return ''
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return value
    }

    return dateFormatter ? dateFormatter.format(date) : date.toISOString()
}

const deleting = ref(false)
const isMobilePreviewOpen = ref(false)

const isPreviewable = computed(() => props.preview?.isPreviewable === true)
const isPdfPreview = computed(() => props.preview?.type === 'pdf')
const isImagePreview = computed(() => props.preview?.type === 'image')

const getStatusVariant = (status) => {
    switch (status) {
        case 'active':
            return 'success'
        case 'expired':
            return 'danger'
        case 'archived':
            return 'neutral'
        default:
            return 'neutral'
    }
}

const archive = () => {
    router.post(`/documents/${props.document.id}/archive`)
}

const restore = () => {
    router.post(`/documents/${props.document.id}/restore`)
}

const openMobilePreview = () => {
    if (!isPreviewable.value) {
        return
    }

    isMobilePreviewOpen.value = true
}

const closeMobilePreview = () => {
    isMobilePreviewOpen.value = false
}

const destroyDocument = () => {
    router.delete(`/documents/${props.document.id}`, {
        onBefore: () => window.confirm('Delete this document? This will permanently remove the file.'),
        onStart: () => {
            deleting.value = true
        },
        onFinish: () => {
            deleting.value = false
        },
    })
}

watch(isMobilePreviewOpen, (isOpen) => {
    if (typeof document === 'undefined') {
        return
    }

    document.body.style.overflow = isOpen ? 'hidden' : ''
})

onBeforeUnmount(() => {
    if (typeof document !== 'undefined') {
        document.body.style.overflow = ''
    }
})
</script>

<template>
    <Head :title="document.title" />

    <AppLayout>
        <div class="space-y-6">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="min-w-0 break-words text-3xl font-bold tracking-tight text-zinc-900">
                            {{ document.title }}
                        </h1>
                        <Badge :variant="getStatusVariant(document.status)">
                            {{ document.status }}
                        </Badge>
                    </div>
                    <p class="mt-1 text-sm text-zinc-500">
                        Uploaded by {{ document.user?.name || 'you' }} • Last updated {{ document.updated_at }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Button :href="`/documents/${document.id}/edit`" class="flex-1 sm:flex-none" variant="secondary">
                        <PencilIcon class="mr-2 h-4 w-4"/>
                        Edit
                    </Button>
                    <Button v-if="document.status === 'archived'" class="flex-1 sm:flex-none" variant="secondary"
                            @click="restore">
                        <ArrowPathIcon class="mr-2 h-4 w-4"/>
                        Restore
                    </Button>
                    <Button v-else class="flex-1 sm:flex-none" variant="secondary" @click="archive">
                        <ArchiveBoxIcon class="mr-2 h-4 w-4"/>
                        Archive
                    </Button>
                    <Button :href="downloadUrl" as="a" class="flex-1 sm:flex-none" target="_blank">
                        <ArrowDownTrayIcon class="mr-2 h-4 w-4"/>
                        Download
                    </Button>
                    <Button
                        :disabled="deleting"
                        class="flex-1 sm:flex-none"
                        type="button"
                        variant="danger"
                        @click="destroyDocument"
                    >
                        <TrashIcon class="mr-2 h-4 w-4"/>
                        {{ deleting ? 'Deleting...' : 'Delete' }}
                    </Button>
                </div>
            </header>

            <Card class="sm:hidden" padding="p-4">
                <div class="space-y-3">
                    <Button
                        v-if="isPreviewable"
                        class="w-full"
                        data-testid="mobile-preview-open"
                        type="button"
                        @click="openMobilePreview"
                    >
                        <EyeIcon class="mr-2 h-4 w-4"/>
                        Open Preview
                    </Button>
                    <Button v-else :href="downloadUrl" class="w-full">
                        <ArrowDownTrayIcon class="mr-2 h-4 w-4"/>
                        Download File
                    </Button>
                </div>
            </Card>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <Card class="hidden sm:block" padding="p-0">
                        <div
                            v-if="isImagePreview"
                            class="flex aspect-4/3 w-full items-center justify-center overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]"
                        >
                            <img :src="previewUrl" alt="Document preview" class="h-full w-full object-contain">
                        </div>
                        <div
                            v-else-if="isPdfPreview"
                            class="aspect-4/3 w-full overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]"
                        >
                            <object :data="previewUrl" class="h-full w-full" type="application/pdf">
                                <div class="flex h-full items-center justify-center bg-zinc-50 p-4">
                                    <Button :href="downloadUrl">
                                        <ArrowDownTrayIcon class="mr-2 h-4 w-4"/>
                                        Download PDF
                                    </Button>
                                </div>
                            </object>
                        </div>
                        <div v-else class="flex h-64 items-center justify-center bg-zinc-50 p-6">
                            <div class="space-y-3 text-center">
                                <p class="text-sm text-zinc-600">Preview is not available for this file type.</p>
                                <Button :href="downloadUrl">
                                    <ArrowDownTrayIcon class="mr-2 h-4 w-4"/>
                                    Download File
                                </Button>
                            </div>
                        </div>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card title="Document Details">
                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="font-medium text-zinc-500">Category</dt>
                                <dd class="mt-1 font-semibold text-zinc-900">
                                    {{ document.category?.name ?? 'Uncategorized' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-zinc-500">Original Filename</dt>
                                <dd class="mt-1 font-mono text-xs text-zinc-900">{{ document.original_filename }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-t border-zinc-100 pt-4">
                                <div>
                                    <dt class="font-medium text-zinc-500">Issue Date</dt>
                                    <dd class="mt-1 font-semibold text-zinc-900">{{ document.issue_date ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-zinc-500">Expiry Date</dt>
                                    <dd class="mt-1 font-semibold text-zinc-900">{{ document.expiry_date ?? '—' }}</dd>
                                </div>
                            </div>
                        </dl>
                    </Card>

                    <Card title="Notes">
                        <p class="whitespace-pre-wrap text-sm leading-relaxed text-zinc-600">
                            {{ document.notes || 'No notes provided for this document.' }}
                        </p>
                    </Card>

                    <Card title="Tags">
                        <div v-if="document.tags?.length" class="flex flex-wrap gap-2">
                            <Badge v-for="tag in document.tags" :key="tag.id" variant="info">
                                {{ tag.name }}
                            </Badge>
                        </div>
                        <p v-else class="text-sm text-zinc-500">No tags assigned.</p>
                    </Card>

                    <Card data-testid="document-activity" title="Activity">
                        <Deferred data="activities">
                            <template #fallback>
                                <ul class="space-y-3">
                                    <li v-for="placeholder in 3" :key="placeholder" class="animate-pulse">
                                        <div class="h-3 w-32 rounded bg-zinc-200"></div>
                                        <div class="mt-2 h-3 w-48 rounded bg-zinc-100"></div>
                                    </li>
                                </ul>
                            </template>

                            <ol v-if="activities?.data?.length" class="space-y-4">
                                <li
                                    v-for="activity in activities.data"
                                    :key="activity.id"
                                    class="border-l-2 border-zinc-200 pl-3"
                                >
                                    <p class="text-sm font-semibold text-zinc-900">{{ activity.label }}</p>
                                    <p class="text-xs text-zinc-500">{{ formatTimestamp(activity.created_at) }}</p>
                                    <p v-if="activity.description" class="mt-1 text-sm text-zinc-600">
                                        {{ activity.description }}
                                    </p>
                                </li>
                            </ol>
                            <p v-else class="text-sm text-zinc-500">No activity recorded yet.</p>
                        </Deferred>
                    </Card>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <div
                v-if="isMobilePreviewOpen"
                class="fixed inset-0 z-50 bg-zinc-950 sm:hidden"
                data-testid="mobile-preview-panel"
            >
                <div class="flex h-full flex-col">
                    <div class="flex items-center justify-between border-b border-zinc-800 px-3 py-2">
                        <button
                            class="rounded-md p-2 text-zinc-200 hover:bg-zinc-800"
                            data-testid="mobile-preview-close"
                            type="button"
                            @click="closeMobilePreview"
                        >
                            <XMarkIcon class="h-5 w-5"/>
                        </button>
                        <div class="flex items-center gap-2">
                            <Button :href="`/documents/${document.id}/edit`" size="sm" variant="secondary">
                                <PencilIcon class="mr-1.5 h-4 w-4"/>
                                Edit
                            </Button>
                            <Button :href="downloadUrl" data-testid="mobile-preview-download" size="sm">
                                <ArrowDownTrayIcon class="mr-1.5 h-4 w-4"/>
                                Download
                            </Button>
                        </div>
                    </div>

                    <div class="min-h-0 flex-1 bg-zinc-900">
                        <div v-if="isImagePreview" class="flex h-full items-center justify-center p-2">
                            <img :src="previewUrl" alt="Document preview" class="h-full w-full object-contain">
                        </div>
                        <object
                            v-else-if="isPdfPreview"
                            :data="previewUrl"
                            class="h-full w-full"
                            type="application/pdf"
                        >
                            <div class="flex h-full items-center justify-center p-6">
                                <Button :href="downloadUrl">
                                    <ArrowDownTrayIcon class="mr-2 h-4 w-4"/>
                                    Download PDF
                                </Button>
                            </div>
                        </object>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>
