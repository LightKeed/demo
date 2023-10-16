<template>
    <transition name="fade" mode="out-in">
        <div
            v-if="($page.props.alert.success || $page.props.alert.error) && show"
            class="relative p-4 mb-4 text-sm border rounded-lg text-blue-800 border-blue-300 bg-blue-50"
            :class="{
                'text-green-800 border-green-300 bg-green-50': $page.props.alert.success,
                'text-red-800 border-red-300 bg-red-50': $page.props.alert.error
            }">
            <p class="pr-6">{{ $page.props.alert.success ?? $page.props.alert.error }}</p>
            <a @click="this.show = false" class="absolute top-4 right-4">
                <Icon name="x" class="min-w-[20px] cursor-pointer" size="5"/>
            </a>
        </div>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            show: false,
        }
    },
    watch: {
        '$page.props.alert': {
            handler() {
                this.show = true
            },
            deep: true,
            immediate: true
        }
    },
}
</script>