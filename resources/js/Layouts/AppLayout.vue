<template>
    <div class="h-full flex flex-col">
        <div class="flex-[1_0_auto] pl-6 pr-6 md:pr-10 py-5">
            <AppHeader v-model:menu="menu"/>
            <div class="flex py-5">
                <AppSidebar v-if="$page.props.auth.user" v-model:menu="menu"/>
                <slot/>
            </div>
        </div>
        <div class="flex-[0_0_auto] px-6 md:px-10 pt-12 pb-6 text-xs font-light text-gray-600">
            <a href="https://digital.tyuiu.ru" target="_blank" class="w-fit flex items-center gap-1 hover:!text-blue-600 transition dark:text-white">
                <Icon name="atom" size="5"/>
                Департамент цифрового развития ТИУ
            </a>
        </div>
    </div>
    <Notification/>
</template>

<script>
export default {
    data() {
        return {
            menu: false
        }
    },
    mounted() {
        if(localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
    },
    watch: {
        '$page.url': function() {
            this.menu = false;
        }
    }
}
</script>