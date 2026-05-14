<script setup>
import {ref} from 'vue'
import {Head, router} from '@inertiajs/vue3'
import {ArchiveBoxIcon, ArrowDownTrayIcon, ArrowPathIcon, PencilIcon, TrashIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../../Layouts/AppLayout.vue'
import Button from '../../Components/UI/Button.vue'
import Badge from '../../Components/UI/Badge.vue'
import Card from '../../Components/UI/Card.vue'

const props = defineProps({
    document: Object,
    previewUrl: String,
    downloadUrl: String,
})

const deleting = ref(false)

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

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <Card padding="p-0">
                        <div class="aspect-4/3 w-full overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]">
                            <iframe :src="previewUrl" class="h-full w-full border-none" title="Document preview"/>
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
                </div>
            </div>
        </div>
    </AppLayout>
</template>
