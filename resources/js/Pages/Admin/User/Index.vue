<template>
    <Head :title="filters.archive ? 'Пользователи - Архив' : 'Пользователи'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Пользователи - Архив' : 'Пользователи' }}
            <IconBtn v-if="!filters.archive" :to="`/admin/users?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
            <IconBtn v-else :to="`/admin/users`" name="database" size="5" title="Основные данные"/>
        </PageTitle>

        <Alert/>

        <MainBtn v-if="!filters.archive" to="/admin/users/create" class="mb-4">Создать пользователя</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.name" label="Поиск по ФИО" placeholder="Введите фио" id="search-name"/>
            <FormTextInput v-model="formSearch.email" label="Поиск по email" placeholder="Введите email" id="search-email"/>
        </PageGroup>
        <PageUserList :users="users.data"/>
        <Pagination :links="users.links" :total="users.total" :length="users.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        users: Object,
    },
    data() {
        return {
            formSearch: {
                name: this.filters.title,
                email: this.filters.email,
                archive: this.filters.archive
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/users', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>