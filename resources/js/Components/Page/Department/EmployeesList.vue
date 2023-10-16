<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="positions.length">
            <PageListItem
                v-for="position in positions"
                :key="position"
                @dblclick="this.$inertia.get(`/admin/employees/${position.employee.id}/edit`)"
            >
                <div class="flex items-center gap-4">
                    <Icon name="user-search"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ position.employee.surname }} {{ position.employee.name }} {{ position.employee.patronymic ?? '' }}</span>
                        </div>
                        <span class="w-fit text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5">
                            {{ position.title }}
                        </span>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(position.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="books" size="5"/>
                                {{ position.employee.level_education ?? 'Нет данных' }}
                                <Tooltip title="Уровень образования"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="badge" size="5"/>
                                {{ position.employee.general_experience }} лет
                                <Tooltip title="Общий стаж"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="badges" size="5"/>
                                {{ position.employee.scientific_experience }} лет
                                <Tooltip title="Научный стаж"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn :to="`/admin/employees/${position.employee.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn @click="destroy(position.id)" name="trash" size="5" variation="danger" title="Удалить"/>
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
        positions: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту должность?')) {
                this.$inertia.delete(`/admin/employees/${id}/position`);
            }
        }
    }
}
</script>