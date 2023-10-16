<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="employees.length">
            <PageListItem
                v-for="employee in employees"
                :key="employee"
                @dblclick="this.hasAccess('personal_edit') ? this.$inertia.get(`/admin/employees/${employee.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="user-search"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ employee.surname }} {{ employee.name }} {{ employee.patronymic ?? '' }}</span>
                        </div>
                        <div v-if="employee.positions.length" class="flex flex-wrap items-center gap-2 mb-2">
                            <span
                                v-for="position in employee.positions"
                                :key="position"
                                class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5"
                            >
                                {{ position.department.title }}, {{ position.title }}
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(employee.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="books" size="5"/>
                                {{ employee.level_education ?? 'Нет данных' }}
                                <Tooltip title="Уровень образования"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="badge" size="5"/>
                                {{ employee.general_experience }} лет
                                <Tooltip title="Общий стаж"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="badges" size="5"/>
                                {{ employee.scientific_experience }} лет
                                <Tooltip title="Научный стаж"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="this.hasAccess('personal_edit')" :to="`/admin/employees/${employee.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="this.hasAccess('personal_delete')" @click="destroy(employee.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="users"/>
            Сотрудники не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        employees: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этого сотрудника?')) {
                this.$inertia.delete(`/admin/employees/${id}`);
            }
        }
    }
}
</script>