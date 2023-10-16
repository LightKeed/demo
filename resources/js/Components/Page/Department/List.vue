<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="departments.length">
            <PageListItem
                v-for="department in departments"
                :key="department"
                @dblclick="this.hasAccess('personal_edit') ? this.$inertia.get(`/admin/departments/${department.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="building"/>
                    <div class="flex flex-col gap-1">
                        <div class="mb-1">
                            <span class="font-bold">{{ department.title }}</span>
                            <span v-if="department.title_short"> ({{ department.title_short }})</span>
                        </div>
                        <span
                            class="w-fit text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5"
                            :class="{ '!bg-gray-600': !department.type }"
                        >
                            {{ department.type ? department.type.title : 'Нет данных' }}
                        </span>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit col-span-2 relative group flex items-center justify-center gap-1">
                                <Icon name="map-pin" size="5"/>
                                {{ getAddress(department.address) }}
                                <Tooltip title="Адрес"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(department.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="users" size="5"/>
                                {{ department.positions.length }}
                                <Tooltip title="Сотрудников"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="this.hasAccess('personal_edit')" :to="`/admin/departments/${department.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="this.hasAccess('personal_delete')" @click="destroy(department.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="building"/>
            Подразделения не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        departments: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это подразделение?')) {
                this.$inertia.delete(`/admin/departments/${id}`);
            }
        }
    }
}
</script>