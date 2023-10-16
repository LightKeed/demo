<template>
    <Head title="Редактирование сотрудника"/>
    <PageBlock>
        <PageTitle>Редактирование сотрудника</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="employee"/>

        <PageGroup class="mb-4">
            <FormTextInput v-model="form.surname" v-model:error="form.errors.surname" label="Фамилия" placeholder="Введите фамилию" id="surname" required/>
            <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Имя" placeholder="Введите имя" id="name" required/>
            <FormTextInput v-model="form.patronymic" v-model:error="form.errors.patronymic" label="Отчество" placeholder="Введите отчество" id="patronymic"/>
            <FormTextInput v-model="form.level_education" v-model:error="form.errors.level_education" label="Уровень образования" placeholder="Введите уровень образования" id="level_education"/>
            <FormTextInput v-model="form.general_experience" v-model:error="form.errors.general_experience" label="Общий стаж" placeholder="Введите общий стаж" type="number" min="0" id="general_experience"/>
            <FormTextInput v-model="form.scientific_experience" v-model:error="form.errors.scientific_experience" label="Научный стаж" placeholder="Введите научный стаж" type="number" min="0" id="scientific_experience"/>
        </PageGroup>

        <FormMediaInput v-model="form.photo_id" v-model:error="form.errors.photo_id" label="Фотография" type="image" mode="single" preview="true"/>

        <PageGroupBtn class="mb-4">
            <MainBtn to="/admin/employees" variation="second">Вернуться назад</MainBtn>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <MainBtn v-if="this.hasAccess('personal_delete')" @click="destroy(employee.id)" variation="danger" type="button">Удалить</MainBtn>
                <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
            </div>
        </PageGroupBtn>

        <PageEmployeePositionList :employee="employee" :departments="departments" :addresses="addresses"/>

        <PageEmployeeAttributeList :employee="employee"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        employee: Object,
        departments: Object,
        addresses: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.employee.id,
                surname: this.employee.surname,
                name: this.employee.name,
                patronymic: this.employee.patronymic,
                level_education: this.employee.level_education,
                general_experience: this.employee.general_experience,
                scientific_experience: this.employee.scientific_experience,
                photo_id: this.employee.photo_id,
                positions: this.employee.positions
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/employees/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        surname: this.employee.surname,
                        name: this.employee.name,
                        patronymic: this.employee.patronymic,
                        level_education: this.employee.level_education,
                        general_experience: this.employee.general_experience,
                        scientific_experience: this.employee.scientific_experience,
                        photo_id: this.employee.photo_id,
                        positions: this.employee.positions
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этого сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}`);
            }
        }
    }
}
</script>