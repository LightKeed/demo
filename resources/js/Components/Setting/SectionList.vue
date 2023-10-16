<template>
    <div class="flex flex-wrap items-center mb-6">
        <Link
            v-for="section in sections" :key="section"
            :href="`/admin/settings/${section.section.toLowerCase()}`"
            class="px-6 py-3 border-b-2 border-gray-300 cursor-pointer hover:border-blue-600 hover:bg-gray-50 transition duration-300 dark:text-white dark:hover:bg-gray-800"
            :class="{ '!border-blue-600 font-bold': $page.url.startsWith(`/admin/settings/${section.section.toLowerCase()}`) }"
        >
            {{ section.title }}
        </Link>
    </div>
</template>

<script>
export default {
    props: {
        sections: [Object, Array]
    }
}
</script>