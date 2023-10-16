<template>
    <ModalConfirm ref="modalConfirm"/>

    <PageEmployeeAttributeForm ref="attributeForm" :employee="employee"/>

    <PageTitle>
        Атрибуты
        <IconBtn @click="toggleForm" name="plus" size="5" variation="success" title="Добавить"/>
    </PageTitle>

    <form
        ref="form"
        @submit.prevent="store"
        class="transition-height duration-500 overflow-hidden"
        :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
    >
        <PageTitle level="2">Добавление атрибута</PageTitle>
        <PageGroup class="mb-4">
            <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="attribute-title"/>
            <FormTextareaInput v-model="form.value" v-model:error="form.errors.value" label="Значение" placeholder="Введите значение" id="attribute-value"/>
        </PageGroup>
        <PageGroupBtn class="mb-4">
            <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
            <MainBtn type="submit" :loading="form.processing">Добавить</MainBtn>
        </PageGroupBtn>
    </form>

    <PageListBody>
        <template v-if="employee.attributes && employee.attributes.length">
            <PageListItem
                v-for="attribute in employee.attributes"
                :key="attribute"
                @dblclick="edit(attribute)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="player-record"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ attribute.title }}</span>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(attribute.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn @click="edit(attribute)" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn @click="destroy(attribute.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="list"/>
            Атрибуты сотрудника не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        employee: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                employee_id: this.employee.id,
                title: null,
                value: null
            })
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        edit(attribute) {
            this.$refs.attributeForm.show(attribute);
        },
        store() {
            this.form.transform((data) => ({
                ...data,
                value: this.form.value,
            }))
                .post('/admin/employees/attribute', {
                    onSuccess: (res) => {
                        if(res.props.alert.success) {
                            this.showForm = false;
                            this.form.reset();
                        }
                    },
                });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот атрибут сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}/attribute`);
            }
        }
    }
}
</script>