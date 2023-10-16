<template>
    <div class="mb-4 max-w-[300px] h-[300px] w-full">
        <img class="rounded-lg w-full h-full object-cover" :src="`/media/images/${id}?w=500&h=500`" alt="photo">
    </div>
</template>

<script setup>
const props = defineProps({
    id: [Number, String]
})
</script>