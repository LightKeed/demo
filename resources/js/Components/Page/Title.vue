<template>
    <div
        class="flex items-center gap-1.5 text-gray-900 font-bold mb-4 text-lg dark:text-white"
        :class="{ '!font-medium': level > 1, '!text-base': level == 2, '!text-sm': level == 3 }"
        v-bind="{ ...$attrs }"
    >
        <slot/>
    </div>
</template>

<script setup>
const props = defineProps({
    level: [String, Number]
})
</script>