<template>
    <div class="input-check">
        <label>
            <input
                type="checkbox" 
                :id="id"
                v-bind="{ ...$attrs }"
                v-model="modelValue"
                @input="$emit('update:modelValue', $event.target.checked)"
            >
            <span>{{ label }}</span>
        </label>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        label: String,
        modelValue: Boolean,
    },
    emits: ['update:modelValue']
}
</script>