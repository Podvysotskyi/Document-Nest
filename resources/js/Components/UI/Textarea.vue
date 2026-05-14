<script setup>
defineProps({
    modelValue: [String, Number],
    label: String,
    error: String,
    placeholder: String,
    required: Boolean,
    rows: {
        type: [String, Number],
        default: 4,
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
        <textarea
            :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': error }"
            :placeholder="placeholder"
            :rows="rows"
            :value="modelValue"
            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm placeholder:text-zinc-400 focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
    </div>
</template>
