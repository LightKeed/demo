<template>
    <div v-if="links" class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mt-4 text-sm text-center">
        <span v-if="total && length" class="text-gray-900 font-medium dark:text-white">Отображено результатов {{ length }} из {{ total }}</span>
        <div v-if="links.length > 3" class="ml-auto">
            <div class="flex flex-wrap justify-center w-full sm:w-auto gap-1">
                <template v-for="(link, key) in links">
                    <div
                        v-if="link.url === null"
                        :key="key"
                        class="text-gray-400 rounded-lg bg-slate-200 px-2 py-1 border border-gray-300 font-bold dark:bg-gray-400 dark:border-gray-500 dark:text-white"
                        :class="{ '!py-0 !px-1 flex items-center justify-center': link.label === 'prev' || link.label === 'next' }"
                    >
                        <Icon v-if="link.label === 'prev'" name="chevron-left" size="5"/>
                        <Icon v-else-if="link.label === 'next'" name="chevron-right" size="5"/>
                        <template v-else>
                            {{ link.label }}
                        </template>
                    </div>
                    <Link
                        v-else
                        :key="`link-${key}`"
                        class="rounded-lg bg-slate-200 px-2 py-1 border border-gray-300 font-bold hover:!bg-blue-700 hover:text-white transition cursor-pointer dark:bg-gray-400 dark:border-gray-500 dark:text-white"
                        :class="{ '!bg-blue-700 text-white !cursor-default': link.active, '!py-0 !px-1 flex items-center justify-center': link.label === 'prev' || link.label === 'next' }"
                        :href="link.url"
                    >
                        <Icon v-if="link.label === 'prev'" name="chevron-left" size="5"/>
                        <Icon v-else-if="link.label === 'next'" name="chevron-right" size="5"/>
                        <template v-else>
                            {{ link.label }}
                        </template>
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        links: Array,
        total: Number,
        length: Number
    }
}
</script>