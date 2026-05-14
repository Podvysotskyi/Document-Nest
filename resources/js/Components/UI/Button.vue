<script setup>
import {Link} from '@inertiajs/vue3'
import {computed} from 'vue'

const props = defineProps({
    href: String,
    as: String,
    method: String,
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    disabled: Boolean,
})

const variants = {
    primary: 'bg-zinc-900 text-white hover:bg-zinc-800 disabled:bg-zinc-400',
    secondary: 'bg-white text-zinc-900 border border-zinc-300 hover:bg-zinc-50 disabled:bg-zinc-50 disabled:text-zinc-400',
    danger: 'bg-red-600 text-white hover:bg-red-700 disabled:bg-red-300',
    ghost: 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 disabled:text-zinc-300',
}

const sizes = {
    sm: 'px-2.5 py-1.5 text-xs',
    md: 'px-3.5 py-2 text-sm',
    lg: 'px-4 py-2.5 text-base',
}

const classes = computed(() => {
    return [
        'inline-flex items-center justify-center rounded-lg font-medium transition-colors focus:outline-hidden focus:ring-2 focus:ring-zinc-900/10 disabled:cursor-not-allowed',
        variants[props.variant],
        sizes[props.size],
    ].join(' ')
})
</script>

<template>
    <Link v-if="href" :as="as" :class="classes" :disabled="disabled" :href="href" :method="method">
        <slot/>
    </Link>
    <button v-else :class="classes" :disabled="disabled" :type="type">
        <slot/>
    </button>
</template>
