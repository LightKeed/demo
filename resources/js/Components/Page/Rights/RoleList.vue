<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="roles.length">
            <PageListItem
                v-for="role in roles"
                :key="role"
                @dblclick="this.$inertia.get(`/admin/settings/rights/role/${role.id}/edit`)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="user-check"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">
                            {{ role.name }}
                        </span>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(role.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn :to="`/admin/settings/rights/role/${role.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn @click="destroy(role.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="user-check"/>
            Роли не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        roles: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту роль?')) {
                this.$inertia.delete(`/admin/settings/rights/role/${id}`);
            }
        }
    }
}
</script>