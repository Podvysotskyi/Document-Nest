<script setup>
import {computed} from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'neutral',
    },
})

const variants = {
    neutral: 'bg-zinc-100 text-zinc-700 border-zinc-200',
    success: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    warning: 'bg-amber-50 text-amber-700 border-amber-200',
    danger: 'bg-red-50 text-red-700 border-red-200',
    info: 'bg-blue-50 text-blue-700 border-blue-200',
}

const classes = computed(() => {
    return [
        'inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium',
        variants[props.variant] || variants.neutral,
    ].join(' ')
})
</script>

<template>
    <span :class="classes">
        <slot/>
    </span>
</template>
