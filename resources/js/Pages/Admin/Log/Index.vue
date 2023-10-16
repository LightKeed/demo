<template>
    <Head title="Логи"/>
    <PageBlock>
        <PageTitle>Логи</PageTitle>
        <Alert/>

        <PageGroup class="mb-4">
            <FormTextInput v-model="formSearch.date" label="Дата" type="date" id="search-date"/>
            <FormSelectInput v-model="formSearch.method" label="Метод" id="search-method">
                <option :value="null">Не выбрано</option>
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </FormSelectInput>
            <FormSelectInput v-model="formSearch.action" label="Действие" id="search-action">
                <option :value="null">Не выбрано</option>
                <option value="viewed">Просмотрено</option>
                <option value="create">Создание</option>
                <option value="update">Обновление</option>
                <option value="delete">Удаление</option>
                <option value="force_delete">Полное удаление</option>
                <option value="restore">Восстановление</option>
                <option value="hidden">Скрытие</option>
                <option value="displayed">Отображение</option>
                <option value="attach">Прикреплено</option>
                <option value="detach">Откреплено</option>
            </FormSelectInput>
            <FormTextInput v-model="formSearch.component" label="Область" placeholder="Введите область" id="search-component"/>
            <FormSelectInput v-model="formSearch.user" label="Пользователь" id="search-user">
                <option :value="null">Не выбрано</option>
                <option v-for="user in users" :key="user" :value="user.id">{{ user.surname }} {{ user.name }} {{ user.patronymic }} (ID: {{ user.id }})</option>
            </FormSelectInput>
            <FormTextInput v-model="formSearch.ip" label="IP-адрес" placeholder="Введите ip-адрес" id="search-ip"/>
        </PageGroup>

        <MainBtn v-if="formSearch.date || formSearch.method || formSearch.action || formSearch.component || formSearch.user || formSearch.ip" @click="reset" class="mb-4">Сбросить фильтр</MainBtn>

        <PageLogList :logs="logs.data"/>
        <Pagination :links="logs.links" :total="logs.total" :length="logs.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        logs: Object,
        users: Object
    },
    data() {
        return {
            formSearch: {
                date: this.filters.date,
                method: this.filters.method,
                action: this.filters.action,
                component: this.filters.component,
                user: this.filters.user,
                ip: this.filters.ip,
            }
        }
    },
    methods: {
        reset() {
            this.formSearch = {
                date: null,
                method: null,
                action: null,
                component: null,
                user: null,
                ip: null
            };
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/logs', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>