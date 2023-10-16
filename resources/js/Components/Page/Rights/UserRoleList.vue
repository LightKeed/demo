<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageTitle>Пользователи с ролью</PageTitle>
    <FormTextInput v-model="search" placeholder="Поиск по ФИО" id="search-name"/>
    <PageListBody>
        <template v-if="usersList.length">
            <PageListItem
                v-for="user in usersList"
                :key="user"
            >
                <div class="flex items-center gap-4">
                    <Icon name="user"/>
                    <div class="font-bold">
                        {{ user.surname }} {{ user.name }} {{ user.patronymic }}
                    </div>
                </div>
                <FormSwitchInput @change.prevent="attach(user.id, roles[user.id])" v-model="roles[user.id]"/>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="users"/>
            Пользователи не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
import axios from 'axios';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        role: String,
        users: Object
    },
    data() {
        return {
            search: null,
            usersList: [],
            roles: []
        }
    },
    mounted() {
        this.getListRoles();
    },
    methods: {
        getListRoles() {
            this.roles = [];
            this.usersList = [];

            this.users.forEach(user => {
                if(!this.search) {
                    this.usersList.push(user);
                } else if(user.surname.toLowerCase().match(this.search.toLowerCase()) || user.name.toLowerCase().match(this.search.toLowerCase()) || (user.patronymic && user.patronymic.toLowerCase().match(this.search.toLowerCase()))) {
                    this.usersList.push(user);
                }
            });

            this.usersList.forEach(user => {
                this.roles[user.id] = Boolean(user.roles.find(r => r.name === this.role))
            });
        },
        async attach(id, status) {
            if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите изменить роль этому пользователю?')) {
                axios.post('/admin/settings/rights/role/attach', {
                    user_id: id,
                    role: this.role,
                    status: status
                })
                    .then((res) => {
                        notificationStore().add('success', 'Роль пользователя успешно изменена', 5000);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При изменении роли пользователя произошла ошибка', 5000);
                        this.roles[id] = !status;
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
                this.roles[id] = !status;
            }
        }
    },
    watch: {
        search(val) {
            this.getListRoles();
        }
    }
}
</script>