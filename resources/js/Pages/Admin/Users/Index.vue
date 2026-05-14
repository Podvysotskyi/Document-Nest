<script setup>
import {computed, ref} from 'vue'
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import {ShieldCheckIcon, UserGroupIcon} from '@heroicons/vue/24/outline'
import AppLayout from '../../../Layouts/AppLayout.vue'
import Badge from '../../../Components/UI/Badge.vue'
import Card from '../../../Components/UI/Card.vue'

const props = defineProps({
    users: Object,
    roles: Array,
})

const page = usePage()
const processingUserId = ref(null)

const rolesError = computed(() => page.props.errors?.role_ids)

const roleBadgeVariant = (name) => {
    if (name === 'Admin') return 'success'
    if (name === 'Developer') return 'info'
    return 'neutral'
}

const userHasRole = (user, roleId) => user.roles.some((role) => role.id === roleId)

const toggleRole = (user, role) => {
    const currentIds = user.roles.map((r) => r.id)
    const nextIds = currentIds.includes(role.id)
        ? currentIds.filter((id) => id !== role.id)
        : [...currentIds, role.id]

    processingUserId.value = user.id

    router.patch(`/admin/users/${user.id}/roles`, {
        role_ids: nextIds,
    }, {
        preserveScroll: true,
        onFinish: () => {
            processingUserId.value = null
        },
    })
}
</script>

<template>
    <Head title="Admin Users"/>

    <AppLayout>
        <div class="space-y-6">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <Link class="text-sm font-medium text-zinc-500 hover:text-zinc-900" href="/admin">Admin</Link>
                        <span class="text-sm text-zinc-300">/</span>
                        <span class="text-sm font-medium text-zinc-900">Users</span>
                    </div>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-900">Users</h1>
                    <p class="mt-1 text-sm text-zinc-500">Review accounts and assign roles.</p>
                </div>
            </header>

            <div
                v-if="rolesError"
                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
            >
                {{ rolesError }}
            </div>

            <Card padding="p-0">
                <div class="divide-y divide-zinc-100 md:hidden">
                    <div v-for="user in users.data" :key="user.id" class="px-4 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <img
                                    v-if="user.avatar_url"
                                    :src="user.avatar_url"
                                    alt="User avatar"
                                    class="h-10 w-10 rounded-full border border-zinc-200 object-cover"
                                >
                                <div
                                    v-else
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-zinc-100 text-sm font-semibold text-zinc-600"
                                >
                                    {{ user.name?.charAt(0) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-zinc-900">{{ user.name }}</p>
                                    <p class="truncate text-xs text-zinc-500">{{ user.email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-1.5">
                            <Badge v-for="role in user.roles" :key="role.id" :variant="roleBadgeVariant(role.name)">
                                {{ role.name }}
                            </Badge>
                            <span v-if="user.roles.length === 0" class="text-xs text-zinc-500">No roles</span>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <label
                                v-for="role in props.roles"
                                :key="role.id"
                                class="inline-flex items-center gap-2 text-sm text-zinc-700"
                            >
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500"
                                    :checked="userHasRole(user, role.id)"
                                    :disabled="processingUserId === user.id"
                                    @change="toggleRole(user, role)"
                                >
                                {{ role.name }}
                            </label>
                        </div>

                        <div class="mt-3 text-xs text-zinc-500">
                            {{ user.documents_count }} documents
                        </div>
                    </div>

                    <div v-if="users.data.length === 0" class="px-4 py-12 text-center text-sm text-zinc-500">
                        <UserGroupIcon class="mx-auto h-9 w-9 text-zinc-300"/>
                        <p class="mt-2 font-semibold text-zinc-700">No users</p>
                    </div>
                </div>

                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-left text-sm text-zinc-900">
                        <thead class="bg-zinc-50/50 text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Google ID</th>
                            <th class="px-6 py-4">Documents</th>
                            <th class="px-6 py-4">Roles</th>
                            <th class="px-6 py-4">Manage</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-zinc-50/50">
                            <td class="px-6 py-4">
                                <div class="flex min-w-0 items-center gap-3">
                                    <img
                                        v-if="user.avatar_url"
                                        :src="user.avatar_url"
                                        alt="User avatar"
                                        class="h-10 w-10 rounded-full border border-zinc-200 object-cover"
                                    >
                                    <div
                                        v-else
                                        class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-zinc-100 text-sm font-semibold text-zinc-600"
                                    >
                                        {{ user.name?.charAt(0) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="truncate font-semibold text-zinc-900">{{ user.name }}</p>
                                            <ShieldCheckIcon v-if="user.is_current_user" class="h-4 w-4 text-emerald-600"/>
                                        </div>
                                        <p class="truncate text-xs text-zinc-500">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-zinc-500">{{ user.google_id ?? 'None' }}</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600">{{ user.documents_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    <Badge v-for="role in user.roles" :key="role.id" :variant="roleBadgeVariant(role.name)">
                                        {{ role.name }}
                                    </Badge>
                                    <span v-if="user.roles.length === 0" class="text-xs text-zinc-500">No roles</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <label
                                        v-for="role in props.roles"
                                        :key="role.id"
                                        class="inline-flex items-center gap-2 text-sm text-zinc-700"
                                    >
                                        <input
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500"
                                            :checked="userHasRole(user, role.id)"
                                            :disabled="processingUserId === user.id"
                                            @change="toggleRole(user, role)"
                                        >
                                        {{ role.name }}
                                    </label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
