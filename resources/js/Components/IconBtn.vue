<template>
    <div class="relative group flex justify-center rounded-lg hover:!bg-inherit">
        <component
            :is="!href && !to ? 'button' : to ? 'Link' : 'a'"
            class="w-max p-1 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition focus:outline-none cursor-pointer"
            :class="{
                '!bg-gray-600 hover:!bg-gray-800 dark:!bg-gray-400 dark:hover:!bg-gray-900': variation === 'second',
                '!bg-green-400 hover:!bg-green-500': variation === 'success',
                '!bg-red-400 hover:!bg-red-500': variation === 'danger',
                '!bg-orange-400 hover:!bg-orange-500': variation === 'warning',
                '!bg-indigo-700 hover:!bg-indigo-800': variation === 'other',
                '!bg-transparent !text-gray-900 dark:!text-white': variation === 'empty'
            }"
            v-bind="{ 'href': to ?? href }"
        >
            <Icon :name="name" :size="size"/>
        </component>
        <Tooltip v-if="title" :title="title"/>
    </div>
</template>

<script setup>
const props = defineProps({
    to: String,
    href: String,
    variation: String,
    name: String,
    size: [String, Number],
    title: String
})
</script>