<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="users.length">
            <PageListItem
                v-for="user in users"
                :key="user"
                :deleted="user.deleted_at"
                @dblclick="this.$inertia.get(`/admin/users/${user.id}/edit`)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="user-search"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ user.surname }} {{ user.name }} {{ user.patronymic ?? '' }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span
                                class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5"
                            >
                                {{ user.email }}
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(user.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="user.deleted_at" @click="restore(user.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <IconBtn v-if="!user.deleted_at" :to="`/admin/users/${user.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-else :to="`/admin/users/${user.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    <IconBtn @click="destroy(user.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="users"/>
            Пользователи не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        users: Object
    },
    methods: {
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этого пользователя?')) {
                this.$inertia.post(`/admin/users/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этого пользователя?')) {
                this.$inertia.delete(`/admin/users/${id}`);
            }
        }
    }
}
</script>