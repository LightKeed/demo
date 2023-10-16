<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="sections.length">
            <PageListItem
                v-for="section in sections"
                :key="section"
                :deleted="section.deleted_at"
                @dblclick="hasAccess('thematic-section_edit') ? this.$inertia.get(`/admin/thematic-sections/${section.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="section"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ section.name }}</span>
                        <div class="flex items-center gap-2 mb-1">
                            Родительский раздел:
                            <span class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5" :class="{ '!bg-gray-600': !section.parent }">
                                {{ section.parent ? section.parent.name  : 'Не указан' }}
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="news" size="5"/>
                                {{ section.news.length }}
                                <Tooltip title="Новостей"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(section.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('thematic-section_restore') && section.deleted_at" @click="restore(section.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccess('thematic-section_edit')">
                        <IconBtn v-if="!section.deleted_at" :to="`/admin/thematic-sections/${section.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/thematic-sections/${section.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-if="hasAccess('thematic-section_delete')" @click="destroy(section.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="section"/>
            Тематические разделы не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        sections: Object
    },
    methods: {
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот тематический раздел?')) {
                this.$inertia.post(`/admin/thematic-sections/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот тематический раздел?')) {
                this.$inertia.delete(`/admin/thematic-sections/${id}`);
            }
        }
    }
}
</script>