<template>
    <ModalConfirm ref="modalConfirm"/>

    <PageEmployeePositionForm ref="positionForm" :departments="departments" :employee="employee" :addresses="addresses"/>

    <PageTitle>
        Должности
        <IconBtn @click="toggleForm" name="plus" size="5" variation="success" title="Добавить"/>
    </PageTitle>

    <form
        ref="form"
        @submit.prevent="store"
        class="transition-height duration-500 overflow-hidden"
        :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
    >
        <PageTitle level="2">Добавление должности</PageTitle>
        <PageGroup class="mb-4">
            <FormSelectInput v-model="form.department_id" v-model:error="form.errors.department_id" label="Подразделение" id="position-department_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="department in departments"
                    :key="department"
                    :value="department.id"
                >
                    {{ department.title }}
                </option>
            </FormSelectInput>
            <FormSelectInput v-model="form.address_id" v-model:error="form.errors.address_id" label="Адрес" desc="При наличии" id="position-address_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="address in addresses"
                    :key="address"
                    :value="address.id"
                >
                    {{ getAddress(address) }}
                </option>
            </FormSelectInput>
            <FormTextInput v-model="form.subdivision" v-model:error="form.errors.subdivision" label="Подотдел" placeholder="Введите подотдел" id="position-subdivision"/>
            <FormTextInput v-model="form.cabinet" v-model:error="form.errors.cabinet" label="Номер кабинета" placeholder="Введите номер кабинета" id="position-cabinet"/>
            <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Должность" placeholder="Введите должность" id="position-title"/>
            <FormTextInput v-model="form.experience" v-model:error="form.errors.experience" label="Стаж" placeholder="Введите стаж" type="number" id="position-experience"/>
            <FormSelectInput v-model="form.status" v-model:error="form.errors.status" label="Статус" id="position-status">
                <option value="0">Уволен</option>
                <option value="1">Работает</option>
            </FormSelectInput>
        </PageGroup>
        <PageGroupBtn class="mb-4">
            <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
            <MainBtn type="submit" :loading="form.processing">Добавить</MainBtn>
        </PageGroupBtn>
    </form>

    <PageListBody class="mb-4">
        <template v-if="employee.positions && employee.positions.length">
            <PageListItem
                v-for="position in employee.positions"
                :key="position"
                @dblclick="edit(position)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="article"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ position.title }}</span>
                        <div class="flex items-center gap-2">
                            Подразделение:
                            <span class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5">
                                {{ position.department ? position.department.title : 'Не указано' }}
                            </span>
                        </div>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(position.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn @click="edit(position)" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn @click="destroy(position.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="article"/>
            Должности сотрудника не найдены
        </PageListItem>
    </PageListBody>
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
            showForm: false,
            form: this.$inertia.form({
                employee_id: this.employee.id,
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
        toggleForm() {
            this.showForm = !this.showForm;
        },
        edit(position) {
            this.$refs.positionForm.show(position);
        },
        store() {
            this.form.post('/admin/employees/position', {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту должность сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}/position`);
            }
        }
    }
}
</script>