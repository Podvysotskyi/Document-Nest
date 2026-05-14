<script setup>
import {computed} from 'vue'
import {Head} from '@inertiajs/vue3'
import Button from '../Components/UI/Button.vue'

const props = defineProps({
    status: Number,
})

const title = computed(() => {
    return {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }[props.status] || 'Error'
})

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status] || 'An unexpected error has occurred.'
})
</script>

<template>
    <Head :title="title"/>

    <div class="flex min-h-screen items-center justify-center bg-zinc-50 px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <p class="text-6xl font-black text-zinc-900">{{ status }}</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-900 sm:text-5xl">{{ title }}</h1>
            <p class="mt-6 text-base leading-7 text-zinc-600">{{ description }}</p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <Button href="/dashboard" size="lg">
                    Go back home
                </Button>
                <a class="text-sm font-semibold text-zinc-900" href="mailto:support@documentnest.com">
                    Contact support <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</template>
