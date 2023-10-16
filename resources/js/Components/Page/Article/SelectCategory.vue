<template>
    <div class="mb-6">
        <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Выберите раздел</div>
        <div
            v-for="(category, key) in categories" :key="category"
            class="bg-gray-50 text-gray-900 border-2 border-gray-300 rounded-lg mb-2 last:mb-0 overflow-hidden dark:bg-gray-600 dark:border-gray-500 dark:text-white"
        >
            <div
                @click.self="show(key)"
                class="flex gap-2 justify-between items-center font-bold px-4 py-2 cursor-pointer"
                :class="{ '!text-white !bg-indigo-400': modelValue === category.id, '!bg-gray-200 dark:!bg-gray-800': category.id === parent_id }"
            >
                <span class="flex items-center gap-2 pointer-events-none">
                    <Icon name="list-details" class="min-w-[24px]"/>
                    <input
                        type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 cursor-pointer pointer-events-auto dark:bg-gray-300"
                        @change="select(category.id)"
                        :checked="category.id === modelValue"
                        :disabled="disabled"
                    >
                    {{ category.name }}
                </span>
                <Icon name="chevron-down" size="5" class="pointer-events-none"/>
            </div>
            <div
                ref="param"
                class="h-0 overflow-hidden duration-500 transition-[height]"
                :style="{ height: key === state ? this.$refs.param[key].scrollHeight + 'px' : '0px' }"
            >
                <template v-for="child_l1 in category.children_categories" :key="child_l1">
                    <label
                        class="flex justify-between items-center px-4 py-2 font-normal cursor-pointer hover:bg-slate-200 border-b border-gray-300 last:border-b-0 transition dark:hover:bg-slate-500"
                        :class="{ '!text-white !bg-indigo-400': modelValue === child_l1.id, 'pointer-events-none': disabled }"
                    >
                        <div class="flex items-center gap-2">
                            <Icon name="category-2" class="min-w-[24px]"/>
                            {{ child_l1.name }}
                        </div>
                        <input
                            type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 cursor-pointer dark:bg-gray-300"
                            @change="select(child_l1.id, category.id)"
                            :checked="child_l1.id === modelValue"
                        >
                    </label>
                    <template v-if="child_l1.children_categories.length">
                        <label
                            v-for="child_l2 in child_l1.children_categories"
                            :key="child_l2"
                            class="flex justify-between items-center px-4 py-2 pl-6 font-normal cursor-pointer hover:bg-slate-200 border-b border-gray-300 last:border-b-0 transition dark:hover:bg-slate-500"
                            :class="{ '!text-white !bg-indigo-400': modelValue === child_l2.id, 'pointer-events-none': disabled }"
                        >
                            <div class="flex items-center gap-2">
                                <Icon name="grip-horizontal" class="min-w-[24px]"/>
                                {{ child_l2.name }}
                            </div>
                            <input
                                type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 cursor-pointer dark:bg-gray-300"
                                @change="select(child_l2.id, category.id)"
                                :checked="child_l2.id === modelValue"
                            >
                        </label>
                    </template>
                </template>
                <div v-if="!category.children_categories.length" class="text-center px-4 py-2">
                    Подразделы не найдены
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        categories: Object,
        modelValue: [String, Number, Boolean, Object, Array],
        disabled: [String, Number, Boolean, Object, Array]
    },
    emits: ['update:modelValue'],
    data() {
        return {
            state: null,
            parent_id: null
        }
    },
    mounted() {
        if (this.modelValue) {
            this.categories.forEach(category => {
                if (category.id === this.modelValue)
                    this.parent_id = category.id;

                category.children_categories.forEach(child_l1 => {
                    if (child_l1.id === this.modelValue)
                        this.parent_id = category.id;

                    child_l1.children_categories.forEach(child_l2 => {
                        if (child_l2.id === this.modelValue)
                            this.parent_id = category.id;
                    });
                });
            });
        }
    },
    methods: {
        show(id) {
            this.state = this.state === id ? null : id;
        },
        select(id, parent_id) {
            this.parent_id = parent_id;
            this.$emit('update:modelValue', this.modelValue === id ? null : id)
        },
    }
}
</script>