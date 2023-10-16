<template>
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center">
            <div v-if="$page.props.auth.user" class="pl-0 md:px-4">
                <div
                    @click="$emit('update:menu', !menu)"
                    class="relative flex flex-col justify-center items-center w-[40px] h-8 cursor-pointer"
                >
                    <div class="absolute w-[12px] h-[2px] rounded bg-slate-600 top-[11px] transition-[width] duration-300 delay-300 dark:bg-white" :class="{ '!w-0 !delay-[0ms]': menu }"></div>
                    <div class="absolute w-[26px] h-[2px] rounded bg-slate-600 top-[16px] transition duration-300 dark:bg-white" :class="{ 'rotate-45 delay-300': menu }"></div>
                    <div class="absolute w-[26px] h-[2px] rounded bg-slate-600 top-[16px] transition duration-300 dark:bg-white" :class="{ '-rotate-45 delay-300': menu }"></div>
                    <div class="absolute w-[12px] h-[2px] rounded bg-slate-600 top-[21px] transition-[width] duration-300 delay-300 dark:bg-white" :class="{ '!w-0 !delay-[0ms]': menu  }"></div>
                </div>
            </div>
            <Link href="/admin">
                <img class="ml-2 md:ml-0 dark:hidden" width="125" height="30" src="/images/slogo-dark.png" alt="logo">
                <img class="ml-2 md:ml-0 hidden dark:block" width="125" height="30" src="/images/slogo-light.png" alt="logo">
            </Link>
        </div>
        <div v-if="$page.props.auth.user" class="flex items-center">
            <AppControlList/>
            <div class="text-sm text-gray-900 font-bold ml-2 pl-2 leading-none border-l border-gray-900 dark:text-white dark:border-white">
                {{ $page.props.auth.user ? $page.props.auth.user.email.split('@')[0] : '' }}
            </div>
        </div>
    </div>

    <AppMenu :show="menu"/>
</template>

<script>
export default {
    props: {
        menu: Boolean
    },
    emits: ['update:menu']
}
</script>
