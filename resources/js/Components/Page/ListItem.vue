<template>
    <div
        class="flex items-center justify-between gap-4 p-4 border-b border-gray-300 last:border-b-0 hover:bg-slate-200 transition dark:hover:bg-slate-500"
        :class="{
            '!bg-red-50 hover:!bg-red-100 dark:!bg-rose-300 dark:hover:!bg-rose-500': deleted,
            '!justify-center !gap-1': empty
        }"
    >
        <slot/>
    </div>
</template>

<script setup>
const props = defineProps({
    deleted: [Boolean, String, Number],
    empty: Boolean
})
</script>