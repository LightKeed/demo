<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="showForm" @cancel="close">
        <PageTitle>Редактирование атрибута</PageTitle>

        <IconBtn @click="close" name="x" title="Закрыть" variation="empty" class="!absolute !top-4 !right-4"/>

        <RecordInfo
            :item="attribute"
            :other="[
                {icon: 'category-2', title: 'Сотрудник', value: `${ employee.surname } ${ employee.name } ${ employee.patronymic ?? '' }`}
            ]"
        />

        <form @submit.prevent="update">
            <PageGroup class="mb-4">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="attribute-title"/>
                <FormTextareaInput v-model="form.value" v-model:error="form.errors.value" label="Значение" placeholder="Введите значение" id="attribute-value"/>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn @click="close" type="button" variation="second">Закрыть</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn @click="destroy(this.form.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </ModalWindow>
</template>

<script>
export default {
    props: {
        employee: Object
    },
    data() {
        return {
            showForm: false,
            attribute: null,
            form: this.$inertia.form({
                id: null,
                employee_id: this.employee.id,
                title: null,
                value: null
            })
        }
    },
    methods: {
        show(attribute) {
            this.attribute = attribute;
            this.form.id = attribute.id;
            this.form.employee_id = attribute.employee_id;
            this.form.title = attribute.title;
            this.form.value = JSON.parse(attribute.value);

            this.showForm = true;
        },
        close() {
            this.form.reset();
            this.showForm = false;
        },
        update() {
            this.form.transform((data) => ({
                ...data,
                value: this.form.value,
            }))
                .put(`/admin/employees/attribute/${this.form.id}`, {
                    onSuccess: (res) => {
                        this.close();
                    },
                });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот атрибут сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}/attribute`, {
                    onSuccess: () => {
                        this.close();
                    }
                });
            }
        }
    }
}
</script>