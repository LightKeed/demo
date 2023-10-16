<template>
    <PageResponsive>
        <div class="bg-gray-50 border-2 border-gray-300 rounded-lg overflow-hidden dark:bg-gray-600 dark:text-white" :class="{ 'min-w-[500px]': !w }">
            <slot/>
        </div>
    </PageResponsive>
</template>

<script>
export default {
    props: {
        w: [String, Number]
    }
}
</script>