<template>
    <div>
        <ModalConfirm ref="modalConfirm"/>
        <PageTitle>
            <Icon name="id" size="5"/>
            Роли пользователя
        </PageTitle>
        <PageListBody class="max-h-[400px] overflow-y-auto">
            <template v-if="rolesList.length">
                <PageListItem
                    v-for="role in rolesList"
                    :key="role"
                >
                    <div class="flex items-center gap-4">
                        <Icon name="user-check"/>
                        <div class="font-bold">
                            {{ role.name }}
                        </div>
                    </div>
                    <FormSwitchInput @change.prevent="attach(user.id, role, rolesAccess[role.id])" v-model="rolesAccess[role.id]"/>
                </PageListItem>
            </template>
            <PageListItem v-else :empty="true">
                <Icon name="user-check"/>
                Роли в системе не найдены
            </PageListItem>
        </PageListBody>
    </div>
</template>

<script>
import axios from 'axios';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        user: Object,
        roles: Object
    },
    data() {
        return {
            rolesList: [],
            rolesAccess: []
        }
    },
    mounted() {
        this.getListRoles();
    },
    methods: {
        getListRoles() {
            this.rolesList = [];
            this.rolesAccess = [];

            this.roles.forEach(role => {
                this.rolesList.push(role);
                this.rolesAccess[role.id] = Boolean(this.user.roles.find(u => u.name === role.name))
            });

        },
        async attach(id, role, status) {
            if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите изменить роль этому пользователю?')) {
                axios.post('/admin/settings/rights/role/attach', {
                    user_id: id,
                    role: role.name,
                    status: status
                })
                    .then((res) => {
                        notificationStore().add('success', 'Роль пользователя успешно изменена', 5000);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При изменении роли пользователя произошла ошибка', 5000);
                        this.rolesAccess[role.id] = !status;
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
                this.rolesAccess[role.id] = !status;
            }
        }
    }
}
</script>