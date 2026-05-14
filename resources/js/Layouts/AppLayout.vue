<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    ChevronDownIcon,
    DocumentTextIcon,
    ShieldCheckIcon,
    UserCircleIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import {computed, onBeforeUnmount, onMounted, ref} from 'vue'

const page = usePage()
const isMobileMenuOpen = ref(false)
const isAccountMenuOpen = ref(false)
const accountMenu = ref(null)

const authUser = computed(() => page.props.auth.user)

const navigationLinks = computed(() => {
    return [
        {
            label: 'Dashboard',
            href: '/dashboard',
            active: page.component === 'Dashboard',
        },
        {
            label: 'Documents',
            href: '/documents',
            active: page.component.startsWith('Documents/'),
        },
        {
            label: 'Categories',
            href: '/categories',
            active: page.component.startsWith('Categories/'),
        },
        {
            label: 'Tags',
            href: '/tags',
            active: page.component.startsWith('Tags/'),
        },
        authUser.value?.is_admin
            ? {
                label: 'Admin',
                href: '/admin',
                active: page.component.startsWith('Admin/'),
            }
            : null,
    ].filter(Boolean)
})

const closeAccountMenu = () => {
    isAccountMenuOpen.value = false
}

const toggleAccountMenu = () => {
    isAccountMenuOpen.value = !isAccountMenuOpen.value
}

const handleDocumentClick = (event) => {
    if (!accountMenu.value?.contains(event.target)) {
        closeAccountMenu()
    }
}

const handleEscape = (event) => {
    if (event.key === 'Escape') {
        closeAccountMenu()
    }
}

onMounted(() => {
    document.addEventListener('click', handleDocumentClick)
    document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleDocumentClick)
    document.removeEventListener('keydown', handleEscape)
})
</script>

<template>
    <div class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased">
        <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 md:gap-8">
                    <button
                        :aria-expanded="isMobileMenuOpen"
                        aria-controls="mobile-navigation"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-700 shadow-xs transition-all hover:bg-zinc-50 hover:text-zinc-900 active:scale-95 md:hidden"
                        data-testid="mobile-navigation-button"
                        type="button"
                        @click="isMobileMenuOpen = ! isMobileMenuOpen"
                    >
                        <span class="sr-only">Toggle navigation</span>
                        <XMarkIcon v-if="isMobileMenuOpen" class="h-5 w-5"/>
                        <Bars3Icon v-else class="h-5 w-5"/>
                    </button>

                    <a class="flex items-center gap-2 font-bold tracking-tight text-zinc-900" href="/">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white">
                            <DocumentTextIcon class="h-5 w-5"/>
                        </div>
                        <span class="hidden min-[380px]:inline">Document Nest</span>
                    </a>

                    <nav class="hidden items-center gap-1 md:flex">
                        <Link
                            v-for="navigationLink in navigationLinks"
                            :key="navigationLink.href"
                            :class="[
                                'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                navigationLink.active ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900'
                            ]"
                            :href="navigationLink.href"
                        >
                            {{ navigationLink.label }}
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <div ref="accountMenu" class="relative">
                        <button
                            :aria-expanded="isAccountMenuOpen"
                            aria-controls="account-menu"
                            class="flex h-10 items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-2 text-zinc-700 shadow-xs transition-all hover:bg-zinc-50 hover:text-zinc-900 active:scale-95 sm:px-3"
                            data-testid="account-menu-button"
                            type="button"
                            @click.stop="toggleAccountMenu"
                        >
                            <img
                                v-if="authUser?.avatar_url"
                                :src="authUser.avatar_url"
                                alt="User avatar"
                                class="h-7 w-7 rounded-full border border-zinc-200 object-cover"
                            >
                            <span
                                v-else
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-200 bg-zinc-100 text-xs font-medium text-zinc-600"
                            >
                                {{ authUser?.name?.charAt(0) }}
                            </span>
                            <span
                                class="hidden min-w-0 max-w-24 truncate text-left text-sm font-medium sm:block sm:max-w-36"
                                data-testid="account-menu-name"
                            >
                                {{ authUser?.name }}
                            </span>
                            <span
                                :class="['hidden shrink-0 text-zinc-500 transition-transform sm:block', isAccountMenuOpen ? 'rotate-180' : '']"
                                data-testid="account-menu-chevron"
                            >
                                <ChevronDownIcon class="h-4 w-4"/>
                            </span>
                        </button>

                        <div
                            v-if="isAccountMenuOpen"
                            id="account-menu"
                            class="absolute right-0 mt-2 w-64 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-lg"
                            data-testid="account-menu"
                        >
                            <div class="border-b border-zinc-100 px-4 py-3">
                                <div class="flex items-center gap-1.5 text-sm font-semibold text-zinc-900">
                                    <ShieldCheckIcon v-if="authUser?.is_admin" class="h-4 w-4 text-emerald-600"/>
                                    <span class="truncate">{{ authUser?.name }}</span>
                                </div>
                                <div class="mt-0.5 truncate text-xs text-zinc-500">{{ authUser?.email }}</div>
                            </div>

                            <div class="py-1">
                                <Link
                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 hover:text-zinc-900"
                                    href="/profile"
                                    @click="closeAccountMenu"
                                >
                                    <UserCircleIcon class="h-4 w-4 text-zinc-500"/>
                                    Profile
                                </Link>
                                <Link
                                    as="button"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 hover:text-zinc-900"
                                    href="/logout"
                                    method="post"
                                    @click="closeAccountMenu"
                                >
                                    <ArrowRightOnRectangleIcon class="h-4 w-4 text-zinc-500"/>
                                    Logout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav
                v-if="isMobileMenuOpen"
                id="mobile-navigation"
                class="border-t border-zinc-200 bg-white px-4 py-3 shadow-xs md:hidden"
            >
                <div class="mx-auto grid w-full max-w-7xl gap-1">
                    <Link
                        v-for="navigationLink in navigationLinks"
                        :key="navigationLink.href"
                        :class="[
                            'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                            navigationLink.active ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900'
                        ]"
                        :href="navigationLink.href"
                        @click="isMobileMenuOpen = false"
                    >
                        {{ navigationLink.label }}
                    </Link>
                </div>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
