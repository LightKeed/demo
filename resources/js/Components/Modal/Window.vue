<template>
    <transition name="fade" mode="out-in">
        <ModalOverlay v-if="show">
            <div @click="$emit('cancel') ?? close" class="fixed w-full h-full"></div>
            <div class="m-2 w-full max-w-[1200px] overflow-auto">
                <PageBlock class="relative delay-500 overflow-x-hidden">
                    <slot/>
                </PageBlock>
            </div>
        </ModalOverlay>
    </transition>
</template>

<script>
export default {
    props: {
        show: Boolean
    },
    emits: ['update:show', 'cancel'],
    methods: {
        close() {
            this.$emit('update:show', false)
        }
    },
    watch: {
        show: {
            handler(val) {
                document.body.style.overflow = val ? 'hidden' : 'auto';
            },
            deep: true
        }
    }
}
</script>
