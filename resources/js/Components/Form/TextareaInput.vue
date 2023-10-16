<template>
    <div :class="$attrs.class">
        <label v-if="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" :for="id">{{ label }}</label>
        <textarea
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:!border-blue-500 block w-full p-2.5 outline-none dark:bg-gray-800 disabled:bg-slate-300 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
        />
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
        modelValue: String,
    },
    emits: ['update:modelValue']
}
</script>