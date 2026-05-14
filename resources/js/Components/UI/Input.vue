<script setup>
import {XMarkIcon} from '@heroicons/vue/24/outline'

defineProps({
    modelValue: [String, Number],
    label: String,
    error: String,
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
    required: Boolean,
})

defineEmits(['update:modelValue'])
</script>

<template>
    <div class="space-y-1.5">
        <label v-if="label" class="text-sm font-medium text-zinc-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input
                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': error }"
                :placeholder="placeholder"
                :type="type"
                :value="modelValue"
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm placeholder:text-zinc-400 focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500 pr-9"
                @input="$emit('update:modelValue', $event.target.value)"
            >
            <div v-if="modelValue" class="absolute inset-y-0 right-0 flex items-center pr-2">
                <button
                    class="p-0.5 text-zinc-400 hover:text-zinc-600 transition-colors"
                    title="Clear"
                    type="button"
                    @click="$emit('update:modelValue', '')"
                >
                    <XMarkIcon class="h-4 w-4"/>
                </button>
            </div>
        </div>
        <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
    </div>
</template>
