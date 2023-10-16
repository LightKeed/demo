<template>
    <div class="mb-6">
        <ModalConfirm ref="modalConfirm"/>
        <div class="relative flex items-center gap-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ label }}
            <IconBtn @click="addValue()" name="plus" size="5" variation="success" title="Добавить значение"/>
        </div>
        <PageListBody>
            <PageListItem v-for="(item, key) in modelValue" :key="key">
                <div class="w-full flex flex-col gap-4">
                    <div
                        v-for="(oItem, index) in item" :key="index"
                        class="flex items-center gap-6"
                    >
                        <IconBtn v-if="index !== 0" @click="removeValueVariable(key, index)" name="x" size="5" variation="empty" title="Удалить переменную"/>
                        <div v-else class="w-[28px]"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                            <FormTextInput v-model="oItem.key" class="!mb-0" placeholder="Введите имя переменной" required/>
                            <FormTextInput v-model="oItem.value" class="!mb-0" placeholder="Введите имя переменной" required/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-1.5 min-w-[62px]">
                    <IconBtn @click="removeValue(key)" name="x" size="5" variation="danger" title="Удалить значение"/>
                    <IconBtn @click="addValueVariable(key)" name="plus" size="5" variation="success" title="Добавить переменную"/>
                    <div class="flex flex-col items-center">
                        <IconBtn @click="moveUp(key)" v-if="key > 0" name="chevron-up" size="5" variation="empty" title="Вверх"/>
                        <IconBtn @click="moveDown(key)" v-if="key < this.modelValue.length - 1" name="chevron-down" size="5" variation="empty" title="Вниз"/>
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
        moveUp(index) {
            if (index <= 0 || index >= this.modelValue.length) {
                return;
            }

            const temp = this.modelValue[index];
            this.modelValue[index] = this.modelValue[index - 1];
            this.modelValue[index - 1] = temp;
        },
        moveDown(index) {
            if (index < 0 || index >= this.modelValue.length - 1) {
                return;
            }

            const temp = this.modelValue[index];
            this.modelValue[index] = this.modelValue[index + 1];
            this.modelValue[index + 1] = temp;
        },
        addValue() {
            this.modelValue.push([{ key: null, value: null }]);
        },
        addValueVariable(index) {
            this.modelValue[index].push({ key: null, value: null });
        },
        async removeValue(index) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это значение?')) {
                this.modelValue.splice(index, 1);
            }
        },
        removeValueVariable(index, varIndex) {
            this.modelValue[index].splice(varIndex, 1);
        }
    }
}
</script>