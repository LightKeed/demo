<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="types.length">
            <PageListItem
                v-for="type in types"
                :key="type"
                @dblclick="this.hasAccess('personal_edit') ? this.$inertia.get(`/admin/department-types/${type.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="clipboard"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ type.title }}</span>
                        <div class="flex items-center gap-2">
                            Подразделений: {{ type.departments.length }}
                        </div>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(type.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="this.hasAccess('personal_edit')" :to="`/admin/department-types/${type.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="this.hasAccess('personal_delete')" @click="destroy(type.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="clipboard-list"/>
            Типы подразделений не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        types: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот тип подразделения?')) {
                this.$inertia.delete(`/admin/department-types/${id}`);
            }
        }
    }
}
</script>