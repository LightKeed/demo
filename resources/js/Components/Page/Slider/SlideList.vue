<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageTitle>Слайды</PageTitle>
    <PageSliderSlideForm :slider="slider"/>
    <PageSliderSlideModalForm ref="modalForm"/>
    <PageListBody>
        <template v-if="slides.length">
            <PageListItem
                v-for="slide in slides"
                :key="slide"
                @dblclick="showForm()"
            >
                <div class="flex items-center gap-4">
                    <img :src="`/media/images/${slide.media_id}?w=150`" width="150" alt="slide">
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ slide.title }}</span>
                        <div class="flex items-center gap-2">
                            Статус:
                            <span v-if="slide.enabled" class="text-xs bg-green-600 text-white rounded-full px-2 py-0.5">Отображен</span>
                            <span v-else class="text-xs bg-red-600 text-white rounded-full px-2 py-0.5">Скрыт</span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="sort-ascending-numbers" size="5"/>
                                {{ slide.sort_order }}
                                <Tooltip title="Номер сортировки"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(slide.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('slider_edit')" @click="$refs.modalForm.show(slide)" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="hasAccess('slider_delete')" @click="destroy(slide.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="photo"/>
            Слайды не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        slider: Object,
        slides: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот слайд?')) {
                this.$inertia.delete(`/admin/sliders/slide/${id}`);
            }
        }
    }
}
</script>