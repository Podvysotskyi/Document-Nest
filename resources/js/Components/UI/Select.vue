<script setup>
import {ChevronDownIcon, XMarkIcon} from '@heroicons/vue/24/outline'

defineProps({
    modelValue: [String, Number],
    label: String,
    error: String,
    options: {
        type: Array,
        required: true,
        // Expected shape: [{ value: '...', label: '...' }]
    },
    placeholder: String,
    required: Boolean,
    name: String,
    showReset: {
        type: Boolean,
        default: true,
    },
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
            <select
                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': error }"
                :name="name"
                :value="modelValue"
                class="w-full appearance-none rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500"
                @change="$emit('update:modelValue', $event.target.value)"
            >
                <option v-if="placeholder" disabled selected value="">{{ placeholder }}</option>
                <option
                    v-for="option in options"
                    :key="option.value"
                    :value="option.value"
                >
                    {{ option.label }}
                </option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 space-x-1">
                <button
                    v-if="modelValue && showReset"
                    class="p-0.5 text-zinc-400 hover:text-zinc-600 transition-colors"
                    title="Clear selection"
                    type="button"
                    @click="$emit('update:modelValue', '')"
                >
                    <XMarkIcon class="h-4 w-4"/>
                </button>
                <div class="pointer-events-none text-zinc-500">
                    <ChevronDownIcon class="h-4 w-4"/>
                </div>
            </div>
        </div>
        <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
    </div>
</template>
