<template>
    <div
        class="absolute z-[100] invisible px-1.5 py-1 text-xs font-medium text-gray-900 border border-gray-300 bg-white rounded-lg shadow-md opacity-0 whitespace-nowrap hover:!invisible group-hover:opacity-100 group-hover:visible transition duration-500 delay-200 select-none"
        :class="{ 'top-full mt-0.5': !position || position === 'bottom', 'left-full ml-0.5': position === 'right', 'right-full mr-0.5': position === 'left', 'bottom-full mb-0.5': position === 'top' }"
    >
        {{ title }}
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
    position: String
})
</script>