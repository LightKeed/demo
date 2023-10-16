<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageTitle>Пользователи с правом</PageTitle>
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
                <FormSwitchInput @change.prevent="attach(user.id, permissions[user.id])" v-model="permissions[user.id]"/>
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
        permission: String,
        users: Object
    },
    data() {
        return {
            search: null,
            usersList: [],
            permissions: []
        }
    },
    mounted() {
        this.getListPermissions();
    },
    methods: {
        getListPermissions() {
            this.permissions = [];
            this.usersList = [];

            this.users.forEach(user => {
                if(!this.search) {
                    this.usersList.push(user);
                } else if(user.surname.toLowerCase().match(this.search.toLowerCase()) || user.name.toLowerCase().match(this.search.toLowerCase()) || (user.patronymic && user.patronymic.toLowerCase().match(this.search.toLowerCase()))) {
                    this.usersList.push(user);
                }
            });

            this.usersList.forEach(user => {
                this.permissions[user.id] = Boolean(user.permissions.find(r => r.name === this.permission))
            });
        },
        async attach(id, status) {
            if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите изменить право этому пользователю?')) {
                axios.post('/admin/settings/rights/permission/attach', {
                    user_id: id,
                    permission: this.permission,
                    status: status
                })
                    .then((res) => {
                        notificationStore().add('success', 'Право пользователя успешно изменено', 5000);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При изменении права пользователя произошла ошибка', 5000);
                        this.permissions[id] = !status;
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
                this.permissions[id] = !status;
            }
        }
    },
    watch: {
        search(val) {
            this.getListPermissions();
        }
    }
}
</script>