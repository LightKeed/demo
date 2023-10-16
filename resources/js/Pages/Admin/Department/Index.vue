<template>
    <Head title="Подразделения"/>
    <PageBlock>
        <PageTitle>Подразделения</PageTitle>

        <Alert/>

        <MainBtn v-if="this.hasAccess('personal_create')" to="/admin/departments/create" class="mb-4">Создать подразделение</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.type" label="Тип подразделения" id="search-type">
                <option :value="null">Не выбрано</option>
                <option value="empty">Без подразделения</option>
                <option
                    v-for="type in types"
                    :key="type"
                    :value="type.id"
                >
                    {{ type.title }}
                </option>
            </FormSelectInput>
        </PageGroup>
        <PageDepartmentList :departments="departments.data"/>
        <Pagination :links="departments.links" :total="departments.total" :length="departments.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        departments: Object,
        types: Object
    },
    data() {
        return {
            formSearch: {
                title: this.filters.title,
                type: this.filters.type
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/departments', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>