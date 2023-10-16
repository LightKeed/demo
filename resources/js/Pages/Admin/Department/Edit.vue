<template>
    <Head title="Редактирование подразделения"/>
    <PageBlock>
        <PageTitle>Редактирование подразделения</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="department"/>

        <PageGroup class="mb-4">
            <FormSelectInput v-model="form.type_id" v-model:error="form.errors.type_id" label="Тип подразделения" id="type_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="type in types"
                    :key="type"
                    :value="type.id"
                >
                    {{ type.title }}
                </option>
            </FormSelectInput>
            <FormSelectInput v-model="form.address_id" v-model:error="form.errors.address_id" label="Адрес" id="address_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="address in addresses"
                    :key="address"
                    :value="address.id"
                >
                    {{ getAddress(address) }}
                </option>
            </FormSelectInput>
            <FormTextInput v-model="form.cabinet" v-model:error="form.errors.cabinet" label="Номер кабинета" placeholder="Введите номер кабинета" id="cabinet"/>
            <FormSelectInput v-model="form.department_id" v-model:error="form.errors.department_id" label="Родительское подразделение" id="department_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="item in departments"
                    :key="item"
                    :value="item.id"
                >
                    {{ item.title }}
                </option>
            </FormSelectInput>
            <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required/>
            <FormTextInput v-model="form.title_short" v-model:error="form.errors.title_short" label="Короткое название" placeholder="Введите короткое название" id="title_short"/>
            <FormTextInput v-model="form.email" v-model:error="form.errors.email" label="Email" placeholder="Введите email" type="email" id="email"/>
            <FormTextInput v-model="form.phone" v-model:error="form.errors.phone" label="Номер телефона" placeholder="Введите номер телефона" id="phone"/>
        </PageGroup>

        <FormMediaInput v-model="form.photo_id" v-model:error="form.errors.photo_id" label="Фотография" type="image" mode="single" preview="true"/>

        <PageGroupBtn class="mb-4">
            <MainBtn to="/admin/departments" variation="second">Вернуться назад</MainBtn>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <MainBtn v-if="this.hasAccess('personal_delete')" @click="destroy(department.id)" variation="danger" type="button">Удалить</MainBtn>
                <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
            </div>
        </PageGroupBtn>

        <PageTitle>Сотрудники</PageTitle>
        <PageDepartmentEmployeesList :positions="department.positions"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        department: Object,
        types: Object,
        addresses: Object,
        departments: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.department.id,
                title: this.department.title,
                title_short: this.department.title_short,
                email: this.department.email,
                phone: this.department.phone,
                cabinet: this.department.cabinet,
                department_id: this.department.department_id,
                photo_id: this.department.photo_id,
                address_id: this.department.address_id,
                type_id: this.department.type_id
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/departments/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        title: this.department.title,
                        title_short: this.department.title_short,
                        email: this.department.email,
                        phone: this.department.phone,
                        cabinet: this.department.cabinet,
                        department_id: this.department.department_id,
                        photo_id: this.department.photo_id,
                        address_id: this.department.address_id,
                        type_id: this.department.type_id
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это подразделение?')) {
                this.$inertia.delete(`/admin/departments/${id}`);
            }
        }
    }
}
</script>