<template>
    <component
        :is="`${name}-icon`"
        :class="`w-${size} h-${size}`"
    />
</template>

<script>
export default {
    props: {
        name: String,
        size: {
            type: [String, Number],
            default: 6
        }
    }
}
</script>