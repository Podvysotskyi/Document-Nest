<script setup>
defineProps({
    title: String,
    subtitle: String,
    padding: {
        type: String,
        default: 'px-4 py-4',
    },
})
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs">
        <div v-if="$slots.header || title" class="border-b border-zinc-200 px-4 py-3">
            <slot name="header">
                <h3 v-if="title" class="text-base font-semibold text-zinc-900">{{ title }}</h3>
                <p v-if="subtitle" class="mt-0.5 text-sm text-zinc-500">{{ subtitle }}</p>
            </slot>
        </div>
        <div :class="['px-4 py-4', padding]">
            <slot/>
        </div>
        <div v-if="$slots.footer" class="border-t border-zinc-200 bg-zinc-50/50 px-4 py-3">
            <slot name="footer"/>
        </div>
    </div>
</template>
