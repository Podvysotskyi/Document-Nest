<script setup>
import {Head, Link, router} from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
    document: Object,
    previewUrl: String,
    downloadUrl: String,
})

const archive = () => {
    router.post(`/documents/${props.document.id}/archive`)
}

const restore = () => {
    router.post(`/documents/${props.document.id}/restore`)
}
</script>

<template>
    <Head :title="document.title" />

    <AppLayout>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">{{ document.title }}</h1>
                <div class="flex items-center gap-2">
                    <Link :href="`/documents/${document.id}/edit`" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100">
                        Edit
                    </Link>
                    <button v-if="document.status === 'archived'" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100"
                            type="button"
                            @click="restore">
                        Restore
                    </button>
                    <button v-else class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100"
                            type="button"
                            @click="archive">
                        Archive
                    </button>
                    <a :href="downloadUrl" class="rounded-md bg-zinc-900 px-3 py-2 text-sm font-medium text-white">Download</a>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-md border border-zinc-200 bg-white p-3 text-sm">
                    <p><span class="font-medium">Status:</span> {{ document.status }}</p>
                    <p><span class="font-medium">Category:</span> {{ document.category?.name ?? '—' }}</p>
                    <p><span class="font-medium">Issue:</span> {{ document.issue_date ?? '—' }}</p>
                    <p><span class="font-medium">Expiry:</span> {{ document.expiry_date ?? '—' }}</p>
                    <p><span class="font-medium">File:</span> {{ document.original_filename }}</p>
                </div>
                <div class="rounded-md border border-zinc-200 bg-white p-3 text-sm md:col-span-2">
                    <p class="mb-2 font-medium">Notes</p>
                    <p class="text-zinc-700">{{ document.notes || 'No notes.' }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-md border border-zinc-200 bg-white">
                <iframe :src="previewUrl" class="h-[70vh] w-full" title="Document preview" />
            </div>
        </div>
    </AppLayout>
</template>
