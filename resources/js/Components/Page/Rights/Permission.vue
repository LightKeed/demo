<template>
    <PageTitle level="2" class="mt-12">
        <Icon name="id" size="5"/>
        Права
    </PageTitle>
    <MainBtn @click="showForm = !showForm" class="mb-4">Создать право</MainBtn>
    <form
        ref="form"
        @submit.prevent="store"
        class="transition-height duration-500 overflow-hidden"
        :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
    >
        <PageTitle level="2">Создание права</PageTitle>

        <PageGroup class="mb-2">
            <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название" placeholder="Введите название права" id="permission-name" required/>
            <FormTextInput v-model="form.display_name" v-model:error="form.errors.display_name" label="Отображаемое имя" placeholder="Введите отображаемое имя" id="permission-displayname" required/>
            <FormTextInput v-model="form.group" v-model:error="form.errors.group" label="Группа" placeholder="Введите название группы" id="permission-group"/>
        </PageGroup>

        <PageGroupBtn class="mb-4">
            <MainBtn @click="showForm = false" type="button" variation="second">Отмена</MainBtn>
            <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
        </PageGroupBtn>
    </form>

    <PageGroup class="mb-2">
        <FormTextInput v-model="formSearch.name" label="Поиск по названию" placeholder="Введите название" id="search-name"/>
        <FormSelectInput v-model="formSearch.group" label="Группа" id="search-group">
            <option :value="null">Все группы</option>
            <option value="empty">Не определена</option>
            <option
                v-for="group in groups"
                :key="group"
                :value="group.group"
            >
                {{ group.group }}
            </option>
        </FormSelectInput>
    </PageGroup>

    <PageRightsPermissionList :permissions="permissions"/>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        permissions: Object,
        groups: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: null,
                display_name: null,
                group: null
            }),
            formSearch: {
                name: this.filters.name,
                group: this.filters.group
            }
        }
    },
    methods: {
        store() {
            this.form.post('/admin/settings/rights/permission', {
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
                this.$inertia.get('/admin/settings/rights', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>