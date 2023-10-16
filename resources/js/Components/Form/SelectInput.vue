<template>
    <div :class="$attrs.class">
        <label v-if="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" :for="id">{{ label }}</label>
        <select
            :id="id" 
            ref="input"
            v-model="selected"
            v-bind="{ ...$attrs, class: null }"
            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:!border-blue-500 block w-full p-2.5 outline-none disabled:bg-slate-300 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
        >
            <slot />
        </select>
        <div v-if="desc" class="mt-1 text-xs text-gray-500">{{ desc }}</div>
        <div v-if="error" class="flex items-center gap-1 mt-1 text-xs text-red-500">
            <Icon @click="$emit('update:error', null);" name="circle-x" class="min-w-[20px] cursor-pointer" size="5"/>
            {{ error }}
        </div>
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
        error: String,
        desc: String,
        modelValue: [String, Number, Boolean, Object, Array],
    },
    emits: ['update:modelValue', 'update:error'],
    data() {
        return {
            selected: this.modelValue,
        }
    },
    watch: {
        selected(selected) {
            this.$emit('update:modelValue', selected)
        },
        modelValue(val) {
            this.selected = val;
        }
    }
}
</script>