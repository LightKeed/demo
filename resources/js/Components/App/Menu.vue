<template>
    <div
        ref="menu"
        class="flex flex-col gap-2 md:mt-0 block md:!h-0 transition-height duration-500 overflow-hidden"
        :style="{ height: show ? menuHeight + navHeight + 'px' : '0px' }"
    >
        <div class="mt-4">
            <AppNavList :show="show" v-model:navHeight="navHeight"/>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: Boolean
    },
    data() {
      return {
          navHeight: 0
      }
    },
    computed: {
        menuHeight() {
            return this.$refs.menu.scrollHeight ?? 0;
        }
    }
}
</script>