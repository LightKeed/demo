<template>
    <div class="mb-6">
        <ModalConfirm ref="modalConfirm"/>
        <div class="relative flex items-center gap-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ label }}
            <IconBtn @click="addValue()" name="plus" size="5" variation="success" title="Добавить значение"/>
        </div>
        <PageListBody>
            <PageListItem v-for="(item, key) in modelValue" :key="key">
                <div class="w-full">
                    <div class="flex items-center gap-4">
                        <div class="group relative flex justify-center">
                            <Icon name="point-filled" size="6"/>
                            <Tooltip title="Название"/>
                        </div>
                        <FormTextInput v-model="item.key" class="!mb-0 w-full" placeholder="Введите имя главной переменной" required/>
                    </div>
                    <div v-if="item.value && item.value.length" class="mt-4 border border-gray-400 rounded-lg p-2 space-y-4 hover:bg-zinc-200 transition">
                        <div v-for="(arrItem, arrIndex) in item.value" :key="arrIndex" class="flex flex-col gap-4 border-b-2 last:border-b-0 border-gray-400 pb-4">
                            <div
                                v-for="(oItem, index) in arrItem" :key="index"
                                class="flex items-center gap-6"
                            >
                                <IconBtn v-if="index !== 0" @click="removeArrValue(key, arrIndex, index)" name="x" size="5" variation="empty" title="Удалить переменную"/>
                                <div v-else class="flex flex-col items-center gap-2">
                                    <IconBtn @click="addArrSubVal(key, arrIndex)" name="plus" size="5" variation="success" title="Добавить подпеременную"/>
                                    <IconBtn @click="removeArrSubVal(key, arrIndex)" name="x" size="5" variation="danger" title="Удалить подпеременную"/>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                                    <FormTextInput v-model="oItem.key" class="!mb-0" placeholder="Введите имя подпеременной" required/>
                                    <FormTextInput v-model="oItem.value" class="!mb-0" placeholder="Введите значение подпеременной" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-1.5 min-w-[62px]">
                    <IconBtn @click="removeValueVariable(key)" name="x" size="5" variation="danger" title="Удалить значение"/>
                    <IconBtn @click="addValueVariable(key)" name="plus" size="5" variation="success" title="Добавить переменную"/>
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
            this.modelValue.push({key: null, value: []});
        },
        addValueVariable(key) {
            this.modelValue[key].value.push([{ key: null, value: null }]);
        },
        addArrSubVal(key, arrIndex) {
            this.modelValue[key].value[arrIndex].push({ key: null, value: null });
        },
        async removeValueVariable(key) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это значение?')) {
                this.modelValue.splice(key, 1);
            }
        },
        removeArrValue(key, arrIndex, index) {
            this.modelValue[key].value[arrIndex].splice(index, 1);
        },
        removeArrSubVal(key, arrIndex) {
            this.modelValue[key].value.splice(arrIndex, 1);
        }
    }
}
</script>