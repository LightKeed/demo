<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="sliders.length">
            <PageListItem
                v-for="slider in sliders"
                :key="slider"
                @dblclick="hasAccess('slider_edit') ? this.$inertia.get(`/admin/sliders/${slider.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="slideshow"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ slider.name }}</span>
                        <div class="flex items-center gap-2">
                            Статус:
                            <span v-if="slider.enabled" class="text-xs bg-green-600 text-white rounded-full px-2 py-0.5">Отображен</span>
                            <span v-else class="text-xs bg-red-600 text-white rounded-full px-2 py-0.5">Скрыт</span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(slider.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="eraser" size="5"/>
                                <span v-if="slider.can_removed" class="font-bold text-rose-400">Можно удалить</span>
                                <span v-else class="font-bold text-indigo-400">Постоянный</span>
                                <Tooltip title="Тип"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('slider_edit')" :to="`/admin/sliders/${slider.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="hasAccess('slider_delete')" @click="destroy(slider.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="slideshow"/>
            Слайдеры не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        sliders: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот слайдер?')) {
                this.$inertia.delete(`/admin/sliders/${id}`);
            }
        }
    }
}
</script>