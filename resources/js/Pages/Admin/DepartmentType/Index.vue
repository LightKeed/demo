<template>
    <Head title="Типы подразделений"/>
    <PageBlock>
        <PageTitle>Типы подразделений</PageTitle>
        <Alert/>
        <template v-if="this.hasAccess('personal_create')">
            <MainBtn v-if="!showForm" @click="toggleForm" class="mb-4">Создать тип</MainBtn>
            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание типа подразделения</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required/>
                </PageGroup>
                <PageGroupBtn class="mb-4">
                    <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
                </PageGroupBtn>
            </form>
        </template>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
        </PageGroup>
        <PageDepartmentTypeList :types="types.data"/>
        <Pagination :links="types.links" :total="types.total" :length="types.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        types: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                title: ''
            }),
            formSearch: {
                title: this.filters.title
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/department-types', {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/department-types', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>