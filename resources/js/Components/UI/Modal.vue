<script setup>
import {onMounted, onUnmounted, watch} from 'vue'
import {XMarkIcon} from '@heroicons/vue/24/outline'

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
    title: String,
    maxWidth: {
        type: String,
        default: 'max-w-2xl',
    },
})

const emit = defineEmits(['close'])

const close = () => emit('close')

const onKeydown = (event) => {
    if (event.key === 'Escape' && props.open) {
        close()
    }
}

onMounted(() => document.addEventListener('keydown', onKeydown))
onUnmounted(() => document.removeEventListener('keydown', onKeydown))

watch(() => props.open, (value) => {
    if (typeof document === 'undefined') return
    document.body.style.overflow = value ? 'hidden' : ''
})
</script>

<template>
    <Teleport to="body">
        <transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="open" class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-zinc-900/40 p-4 sm:p-8" @click.self="close">
                <div :class="['w-full rounded-lg border border-zinc-200 bg-white shadow-lg', maxWidth]" role="dialog" aria-modal="true">
                    <header v-if="title || $slots.title" class="flex items-center justify-between gap-4 border-b border-zinc-200 px-5 py-4">
                        <h2 class="text-base font-semibold text-zinc-900">
                            <slot name="title">{{ title }}</slot>
                        </h2>
                        <button aria-label="Close" class="rounded-md p-1 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900" type="button" @click="close">
                            <XMarkIcon class="h-5 w-5"/>
                        </button>
                    </header>
                    <div class="px-5 py-5">
                        <slot/>
                    </div>
                    <footer v-if="$slots.footer" class="flex items-center justify-end gap-2 border-t border-zinc-200 bg-zinc-50/50 px-5 py-3">
                        <slot name="footer"/>
                    </footer>
                </div>
            </div>
        </transition>
    </Teleport>
</template>
