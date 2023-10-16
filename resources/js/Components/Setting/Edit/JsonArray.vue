<template>
    <div class="mb-6">
        <ModalConfirm ref="modalConfirm"/>
        <div class="relative flex items-center gap-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ label }}
            <IconBtn @click="addValue()" name="plus" size="5" variation="success" title="Добавить значение"/>
        </div>
        <PageListBody class="max-h-[500px] overflow-y-auto">
            <PageListItem>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 w-full">
                    <div
                        v-for="(item, key) in modelValue" :key="key"
                        class="flex items-center gap-2 w-full"
                    >
                        <IconBtn @click="removeValue(key)" name="x" size="4" variation="empty" title="Удалить значение"/>
                        <FormTextInput v-model="modelValue[key]" class="!mb-0 w-full" placeholder="Введите значение" required/>
                    </div>
                </div>
            </PageListItem>
            <PageListItem v-if="!modelValue.length" :empty="true">
                <Icon name="table"/>
                Значения не найдены
            </PageListItem>
        </PageListBody>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: 'Переменная'
        },
        modelValue: [Object, Array],
    },
    methods: {
        addValue() {
            this.modelValue.push('');
        },
        async removeValue(key) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это значение?')) {
                this.modelValue.splice(key, 1);
            }
        }
    }
}
</script>