<template>
    <Head title="Сотрудники"/>
    <PageBlock>
        <PageTitle>Сотрудники</PageTitle>
        
        <Alert/>

        <MainBtn v-if="this.hasAccess('personal_create')" to="/admin/employees/create" class="mb-4">Создать сотрудника</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Поиск по ФИО" placeholder="Введите фио" id="search-title"/>
            <FormSelectInput v-model="formSearch.department" label="Подразделение" id="search-department">
                <option :value="null">Не выбрано</option>
                <option value="empty">Без подразделения</option>
                <option
                    v-for="department in departments"
                    :key="department"
                    :value="department.id"
                >
                    {{ department.title }}
                </option>
            </FormSelectInput>
        </PageGroup>
        <PageEmployeeList :employees="employees.data"/>
        <Pagination :links="employees.links" :total="employees.total" :length="employees.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        employees: Object,
        departments: Object
    },
    data() {
        return {
            formSearch: {
                title: this.filters.title,
                department: this.filters.department
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/employees', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>