<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="showForm" @cancel="close">
        <PageTitle>Редактирование должности</PageTitle>

        <IconBtn @click="close" name="x" title="Закрыть" variation="empty" class="!absolute !top-4 !right-4"/>

        <RecordInfo
            :item="position"
            :other="[
                {icon: 'category-2', title: 'Сотрудник', value: `${ employee.surname } ${ employee.name } ${ employee.patronymic ?? '' }`}
            ]"
        />

        <form @submit.prevent="update">
            <PageGroup class="mb-4">
                <FormSelectInput v-model="form.department_id" v-model:error="form.errors.department_id" label="Подразделение" id="position-form-department_id">
                    <option :value="null">Не выбрано</option>
                    <option
                        v-for="department in departments"
                        :key="department"
                        :value="department.id"
                    >
                        {{ department.title }}
                    </option>
                </FormSelectInput>
                <FormSelectInput v-model="form.address_id" v-model:error="form.errors.address_id" label="Адрес" desc="При наличии" id="position-form-address_id">
                    <option :value="null">Не выбрано</option>
                    <option
                        v-for="address in addresses"
                        :key="address"
                        :value="address.id"
                    >
                        {{ getAddress(address) }}
                    </option>
                </FormSelectInput>
                <FormTextInput v-model="form.subdivision" v-model:error="form.errors.subdivision" label="Подотдел" placeholder="Введите подотдел" id="position-form-subdivision"/>
                <FormTextInput v-model="form.cabinet" v-model:error="form.errors.cabinet" label="Номер кабинета" placeholder="Введите номер кабинета" id="position-form-cabinet"/>
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Должность" placeholder="Введите должность" id="position-form-title"/>
                <FormTextInput v-model="form.experience" v-model:error="form.errors.experience" label="Стаж" placeholder="Введите стаж" type="number" id="position-form-experience"/>
                <FormSelectInput v-model="form.status" v-model:error="form.errors.status" label="Статус" id="position-form-status">
                    <option value="0">Уволен</option>
                    <option value="1">Работает</option>
                </FormSelectInput>
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
        departments: Object,
        employee: Object,
        addresses: Object
    },
    data() {
        return {
            showForm: false,
            position: null,
            form: this.$inertia.form({
                id: null,
                employee_id: null,
                department_id: null,
                address_id: null,
                subdivision: null,
                cabinet: null,
                title: null,
                experience: 0,
                status: 1,
            })
        }
    },
    methods: {
        show(position) {
            this.position = position;
            this.form.id = position.id;
            this.form.employee_id = position.employee_id;
            this.form.department_id = position.department_id;
            this.form.address_id = position.address_id;
            this.form.subdivision = position.subdivision;
            this.form.cabinet = position.cabinet;
            this.form.title = position.title;
            this.form.experience = position.experience;
            this.form.status = position.status;

            this.showForm = true;
        },
        close() {
            this.form.reset();
            this.showForm = false;
        },
        update() {
            this.form.put(`/admin/employees/position/${this.form.id}`, {
                onSuccess: (res) => {
                    this.close();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту должность сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}/position`, {
                    onSuccess: () => {
                        this.close();
                    }
                });
            }
        }
    }
}
</script>