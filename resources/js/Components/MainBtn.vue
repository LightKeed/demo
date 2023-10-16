<template>
    <component
        :is="!$attrs.href && !to ? 'button' : to ? 'Link' : 'a'"
        class="flex items-center gap-1 w-max text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 transition focus:outline-none"
        :class="{
            '!bg-gray-600 hover:!bg-gray-800 dark:!bg-gray-500 dark:hover:!bg-gray-900': variation === 'second',
            '!bg-green-400 hover:!bg-green-500': variation === 'success',
            '!bg-red-400 hover:!bg-red-500': variation === 'danger',
            '!bg-orange-400 hover:!bg-orange-500': variation === 'warning',
            '!bg-indigo-700 hover:!bg-indigo-800': variation === 'other',
            'opacity-80': $attrs.disabled
        }"
        :disabled="loading || $attrs.disabled"
        v-bind="{ ...$attrs, 'href': to }"
    >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot/>
    </component>
</template>

<script setup>
const props = defineProps({
    to: String,
    variation: String,
    loading: Boolean
})
</script>