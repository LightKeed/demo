<template>
    <label class="flex relative items-center w-fit cursor-pointer" :class="$attrs.class">
        <input
            class="sr-only peer"
            type="checkbox"
            v-bind="{ ...$attrs }"
            :value="modelValue ?? $attrs.value"
            :checked="modelValue"
            @input="$emit('update:modelValue', $event.target.checked)"
        >
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 dark:bg-gray-500"
            :class="{ '!bg-gray-200': $attrs.disabled }">
        </div>
        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</span>
    </label>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        label: String,
        modelValue: [Boolean, Number, String],
    },
    emits: ['update:modelValue']
}
</script>