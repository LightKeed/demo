<template>
    <div class="mb-2 last:mb-0" :class="$attrs.class" :style="$attrs.style">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
        <VueMultiselect
            v-model="selected"
            :options="options"
            :multiple="true"
            :searchable="false"
            :close-on-select="false"
            :clear-on-select="false"
            placeholder="Не выбрано"
            select-label="Выбрать"
            deselect-label="Удалить"
            selected-label="Выбрано"
            :label="itemLabel"
            :track-by="track"
            :showNoOptions="false"
            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:!border-blue-500 block w-full outline-none dark:border-gray-600 dark:bg-gray-800"
            v-bind="{ ...$attrs, class: null, style: null }"
        >
        </VueMultiselect>
        <div v-if="desc" class="mt-1 text-xs text-gray-500">{{ desc }}</div>
        <div v-if="error" class="flex items-center gap-1 mt-1 text-xs text-red-500">
            <Icon @click="$emit('update:error', null);" name="circle-x" class="min-w-[20px] cursor-pointer" size="5"/>
            {{ error }}
        </div>
    </div>
</template>

<script>
import VueMultiselect from 'vue-multiselect'

export default {
    inheritAttrs: false,
    props: {
        id: String,
        error: String,
        label: String,
        desc: String,
        options: Array,
        modelValue: [Array, String, Number, Boolean],
        itemLabel: String,
        track: String
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
    },
    components: {
        VueMultiselect
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>