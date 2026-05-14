<script setup>
import {watch} from 'vue'
import {Head, Link, router, useForm} from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import {ChevronDownIcon, ChevronUpIcon, CloudArrowUpIcon, DocumentIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../../Layouts/AppLayout.vue'
import Button from '../../Components/UI/Button.vue'
import Badge from '../../Components/UI/Badge.vue'
import Card from '../../Components/UI/Card.vue'
import Input from '../../Components/UI/Input.vue'
import Select from '../../Components/UI/Select.vue'

const props = defineProps({
    documents: Object,
    filters: Object,
    categories: Array,
    tags: Array,
})

const form = useForm({
    q: props.filters.q || '',
    category_id: props.filters.category_id || '',
    tag_id: props.filters.tag_id || '',
    status: props.filters.status || '',
    expiry_from: props.filters.expiry_from || '',
    expiry_to: props.filters.expiry_to || '',
    sort: props.filters.sort || 'newest',
    direction: props.filters.direction || '',
})

const categoryOptions = [
    {value: 'uncategorized', label: 'Uncategorized'},
    ...props.categories.map((category) => ({value: category.id, label: category.name})),
]

const tagOptions = props.tags.map((tag) => ({value: tag.id, label: tag.name}))

const sortBy = (field) => {
    if (form.sort === field) {
        form.direction = form.direction === 'asc' ? 'desc' : 'asc'
    } else {
        form.sort = field
        form.direction = 'asc'
    }
}

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

const applyFilters = () => {
    form.get('/documents', {
        preserveState: true,
        replace: true,
    })
}

watch(
    () => form.data(),
    debounce(() => {
        applyFilters()
    }, 300),
    {deep: true}
)
</script>

<template>
    <Head title="Documents" />

    <AppLayout>
        <div class="space-y-6">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Documents</h1>
                    <p class="mt-1 text-sm text-zinc-500">Manage and filter your document collection.</p>
                </div>
                <Button class="w-full sm:w-auto" href="/documents/create">
                    <CloudArrowUpIcon class="mr-2 h-5 w-5"/>
                    Upload Document
                </Button>
            </header>

            <Card padding="px-4 py-3">
                <form class="grid gap-3 md:grid-cols-2 xl:grid-cols-7" @submit.prevent>
                    <div class="md:col-span-2 xl:col-span-2">
                        <Input
                            v-model="form.q"
                            :error="form.errors.q"
                            label="Search"
                            placeholder="Search documents..."
                        />
                    </div>
                    <Select
                        v-model="form.category_id"
                        :error="form.errors.category_id"
                        :options="categoryOptions"
                        label="Category"
                        placeholder="All categories"
                    />
                    <Select
                        v-model="form.tag_id"
                        :error="form.errors.tag_id"
                        :options="tagOptions"
                        label="Tag"
                        placeholder="All tags"
                    />
                    <Select
                        v-model="form.status"
                        :error="form.errors.status"
                        :options="[
                            { value: 'active', label: 'Active' },
                            { value: 'expired', label: 'Expired' },
                            { value: 'archived', label: 'Archived' }
                        ]"
                        label="Status"
                        placeholder="All statuses"
                    />
                    <Input
                        v-model="form.expiry_from"
                        :error="form.errors.expiry_from"
                        label="Expiry from"
                        type="date"
                    />
                    <Input
                        v-model="form.expiry_to"
                        :error="form.errors.expiry_to"
                        label="Expiry to"
                        type="date"
                    />
                </form>
            </Card>

            <Card padding="p-0">
                <div :class="['overflow-x-auto transition-opacity', form.processing ? 'opacity-60' : 'opacity-100']">
                    <table class="w-full text-left text-sm text-zinc-900">
                        <thead class="bg-zinc-50/50 text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        <tr>
                            <th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"
                                @click="sortBy('title')">
                                <div class="flex items-center gap-1">
                                    Document
                                    <template v-if="form.sort === 'title'">
                                        <ChevronUpIcon v-if="form.direction === 'asc'" class="h-3 w-3"/>
                                        <ChevronDownIcon v-else class="h-3 w-3"/>
                                    </template>
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"
                                @click="sortBy('category')">
                                <div class="flex items-center gap-1">
                                    Category
                                    <template v-if="form.sort === 'category'">
                                        <ChevronUpIcon v-if="form.direction === 'asc'" class="h-3 w-3"/>
                                        <ChevronDownIcon v-else class="h-3 w-3"/>
                                    </template>
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"
                                @click="sortBy('status')">
                                <div class="flex items-center gap-1">
                                    Status
                                    <template v-if="form.sort === 'status'">
                                        <ChevronUpIcon v-if="form.direction === 'asc'" class="h-3 w-3"/>
                                        <ChevronDownIcon v-else class="h-3 w-3"/>
                                    </template>
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"
                                @click="sortBy('expiry_date')">
                                <div class="flex items-center gap-1">
                                    Expiry
                                    <template v-if="form.sort === 'expiry_date'">
                                        <ChevronUpIcon v-if="form.direction === 'asc'" class="h-3 w-3"/>
                                        <ChevronDownIcon v-else class="h-3 w-3"/>
                                    </template>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                        <tr
                            v-for="document in documents.data"
                            :key="document.id"
                            class="group cursor-pointer hover:bg-zinc-50/50 transition-colors"
                            @click="router.visit(`/documents/${document.id}`)"
                        >
                            <td class="px-6 py-4">
                                <div class="font-semibold text-zinc-900 group-hover:text-zinc-600">
                                    {{ document.title }}
                                </div>
                                <p class="text-xs text-zinc-500">Updated {{ document.updated_at }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <Badge v-if="document.category" variant="neutral">
                                    {{ document.category.name }}
                                </Badge>
                                <span v-else class="text-zinc-400">—</span>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :variant="getStatusVariant(document.status)">
                                    {{ document.status }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                    <span
                                        :class="document.status === 'expired' ? 'font-medium text-red-600' : 'text-zinc-600'">
                                        {{ document.expiry_date ?? '—' }}
                                    </span>
                            </td>
                        </tr>
                        <tr v-if="documents.data.length === 0">
                            <td class="px-6 py-12 text-center text-zinc-500" colspan="4">
                                <div class="flex flex-col items-center gap-2">
                                    <DocumentIcon class="h-8 w-8 text-zinc-300"/>
                                    <p>No documents found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <template v-if="documents.links.length > 3" #footer>
                    <div class="flex items-center justify-between">
                        <!-- Simplified pagination for brevity, or reuse a dedicated component if available -->
                        <div class="text-xs text-zinc-500">
                            Showing {{ documents.from }} to {{ documents.to }} of {{ documents.total }} results
                        </div>
                        <div class="flex gap-1">
                            <Link
                                v-for="link in documents.links"
                                :key="link.label"
                                :class="[
                                    'rounded px-2 py-1 text-xs font-medium',
                                    link.active ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100',
                                    !link.url ? 'pointer-events-none opacity-50' : ''
                                ]"
                                :href="link.url"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </template>
            </Card>
        </div>
    </AppLayout>
</template>
