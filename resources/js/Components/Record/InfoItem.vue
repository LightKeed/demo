<template>
    <div class="rounded-lg border border-gray-300 border-l-8 border-l-indigo-600 text-indigo-600 p-4 dark:text-indigo-400 dark:border-gray-500 dark:border-l-indigo-400">
        <div class="flex items-center gap-1.5 font-medium !text-gray-500 mb-1 dark:!text-white">
            <Icon :name="icon" size="5"/>
            {{ title }}
        </div>
        <span class="font-bold md:text-base">{{ value }}</span>
    </div>
</template>

<script setup>
const props = defineProps({
    icon: {
        type: String,
        default: 'info-circle'
    },
    title: {
        type: String,
        default: 'Информация'
    },
    value: {
        type: [String, Number],
        default: 'Нет данных'
    }
})
</script>