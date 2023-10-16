<template>
    <div>
        <ModalConfirm ref="modalConfirm"/>
        <PageTitle>
            <Icon name="user-check" size="5"/>
            Права пользователя
        </PageTitle>

        <div class="grid md:grid-cols-2 mb-2 gap-4">
            <FormTextInput v-model="search.name" label="Поиск по названию" placeholder="Введите название" id="search-name"/>
            <FormSelectInput v-model="search.group" label="Группа" id="search-group">
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
        </div>

        <PageListBody class="max-h-[400px] overflow-y-auto">
            <template v-if="permissionsList.length">
                <PageListItem
                    v-for="permission in permissionsList"
                    :key="permission"
                >
                    <div class="flex items-center gap-4">
                        <Icon name="id"/>
                        <div class="font-bold">
                            {{ permission.display_name }} <span class="font-normal text-xs">({{ permission.name }} - {{ permission.group ?? 'Не определена' }})</span>
                        </div>
                    </div>
                    <FormSwitchInput @change.prevent="attach(user.id, permission, permissionsAccess[permission.id])" v-model="permissionsAccess[permission.id]"/>
                </PageListItem>
            </template>
            <PageListItem v-else :empty="true">
                <Icon name="id"/>
                Права в системе не найдены
            </PageListItem>
        </PageListBody>
    </div>
</template>

<script>
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        user: Object,
        permissions: Object,
        groups: Object
    },
    data() {
        return {
            permissionsList: [],
            permissionsAccess: [],
            search: {
                name: null,
                group: null
            }
        }
    },
    mounted() {
        this.getListPermissions();
    },
    methods: {
        getListPermissions() {
            this.permissionsList = [];
            this.permissionsAccess = [];

            this.permissions.forEach(permission => {
                if(!this.search.name && !this.search.group) {
                    this.permissionsList.push(permission);
                } else {
                    if(this.search.group && this.search.name) {
                        if(
                            ((this.search.group === 'empty' && !permission.group) || this.search.group === permission.group) &&
                            (this.search.name && permission.name.toLowerCase().match(this.search.name.toLowerCase()) || permission.display_name.toLowerCase().match(this.search.name.toLowerCase()))
                        ) {
                            this.permissionsList.push(permission);
                        }
                    } else if(this.search.name && (permission.name.toLowerCase().match(this.search.name.toLowerCase()) || permission.display_name.toLowerCase().match(this.search.name.toLowerCase()))) {
                        this.permissionsList.push(permission);
                    } else if(this.search.group && ((this.search.group === 'empty' && !permission.group) || this.search.group === permission.group)) {
                       this.permissionsList.push(permission);
                    }
                }
                this.permissionsAccess[permission.id] = Boolean(this.user.permissions.find(u => u.name === permission.name));
            });
        },
        async attach(id, permission, status) {
            if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите изменить право этому пользователю?')) {
                axios.post('/admin/settings/rights/permission/attach', {
                    user_id: id,
                    permission: permission.name,
                    status: status
                })
                    .then((res) => {
                        router.reload({ only: ['user', 'permissions', 'roles'] })
                        notificationStore().add('success', 'Право пользователя успешно изменено', 5000);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При изменении права пользователя произошла ошибка', 5000);
                        this.permissionsAccess[permission.id] = !status;
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
                this.permissionsAccess[permission.id] = !status;
            }
        }
    },
    watch: {
        search: {
            handler(val){
                this.getListPermissions();
            },
            deep: true
        }
    }
}
</script>