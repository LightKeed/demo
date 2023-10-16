<template>
    <div class="mb-6">
        <ModalConfirm ref="modalConfirm"/>
        <div class="relative flex items-center gap-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ label }}
            <IconBtn @click="addValue()" name="plus" size="5" variation="success" title="Добавить значение"/>
        </div>
        <PageListBody>
            <PageListItem v-for="(item, key) in modelValue" :key="key">
                <div class="flex items-center gap-6 w-full">
                    <IconBtn v-if="!item.type" @click="toggleType(key)" name="point" variation="empty" title="Изменить тип"/>
                    <IconBtn v-else @click="toggleType(key)" name="grip-vertical" variation="empty" title="Изменить тип"/>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <FormTextInput v-model="item.key" class="!mb-0" placeholder="Введите имя переменной" required/>
                        <FormTextInput v-if="!item.type" v-model="item.value" class="!mb-0" placeholder="Введите имя переменной" required/>
                        <div v-else class="space-y-4">
                            <div v-for="(subValue, index) in item.value" :key="index" class="flex items-center gap-2 w-full">
                                <FormTextInput v-model="item.value[index]" placeholder="Введите подзначение" class="flex-1 !mb-0" required/>
                                <IconBtn v-if="item.type && index !== 0" @click="removeSubValue(key, index)" name="x" size="5" variation="empty" title="Удалить подзначение"/>
                                <div v-else-if="item.type" class="w-[28px]"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-1.5 min-w-[62px]">
                    <IconBtn @click="removeValue(key)" name="x" size="5" variation="danger" title="Удалить значение"/>
                    <IconBtn v-if="item.type" @click="addSubValue(key)" name="plus" size="5" variation="success" title="Добавить подзначение"/>
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
            this.modelValue.push({ type: false, key: null, value: null });
        },
        addSubValue(index) {
            this.modelValue[index].value.push(null);
        },
        async removeValue(index) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это значение?')) {
                this.modelValue.splice(index, 1);
            }
        },
        removeSubValue(index, subIndex) {
            this.modelValue[index].value.splice(subIndex, 1);
        },
        toggleType(index) {
            if(!this.modelValue[index].type) {
                this.modelValue[index].type = true;
                this.modelValue[index].value = [this.modelValue[index].value];
            } else {
                this.modelValue[index].type = false;
                this.modelValue[index].value = this.modelValue[index].value[0] ?? null;
            }
        }
    }
}
</script>