<template>
    <div class="px-4 hidden md:block">
        <div
            class="flex flex-col space-y-2 transition-[width] duration-500"
            :class="{ 'w-[250px]': menu, 'w-[40px]': !menu }"
        >
            <AppNavList :show="menu" @open="$emit('update:menu', true)"/>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        menu: Boolean
    },
    emits: ['update:menu']
}
</script>

<style>
@keyframes top {
    0% {
        transform: translateY(-12px);
        opacity: 0;
    }
    100% {
        opacity: 1;
        transform: none;
    }
}
</style>