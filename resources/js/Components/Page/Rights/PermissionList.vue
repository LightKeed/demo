<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody class="max-h-[500px] overflow-y-auto">
        <template v-if="permissions.length">
            <PageListItem
                v-for="permission in permissions"
                :key="permission"
                @dblclick="this.$inertia.get(`/admin/settings/rights/permission/${permission.id}/edit`)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="id"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">
                            {{ permission.display_name }}
                        </span>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="notes" size="5"/>
                                {{ permission.name }}
                                <Tooltip title="Название права"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="folder" size="5"/>
                                {{ permission.group ?? 'Не определена' }}
                                <Tooltip title="Группа"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(permission.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn :to="`/admin/settings/rights/permission/${permission.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn @click="destroy(permission.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="id"/>
            Права не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        permissions: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это право?')) {
                this.$inertia.delete(`/admin/settings/rights/permission/${id}`);
            }
        }
    }
}
</script>