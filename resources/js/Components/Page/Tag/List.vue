<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="tags.length">
            <PageListItem
                v-for="tag in tags"
                :key="tag"
                :deleted="tag.deleted_at"
                @dblclick="hasAccess('tag_edit') ? this.$inertia.get(`/admin/tags/${tag.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="tag"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ tag.name }}</span>
                        <div class="flex items-center gap-2">
                            Область действия:
                            <span class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5" :class="{ '!bg-gray-600': !tag.scope }">
                                {{ tag.scope === 'news' ? 'Новости' : tag.scope === 'events' ? 'Мероприятия' : 'Не указана' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            Элементов по тегу: {{ tag.elements.length }}
                        </div>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(tag.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('tag_restore') && tag.deleted_at" @click="restore(tag.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccess('tag_edit')">
                        <IconBtn v-if="!tag.deleted_at" :to="`/admin/tags/${tag.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/tags/${tag.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-if="hasAccess('tag_delete')" @click="destroy(tag.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="tags"/>
            Теги не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        tags: Object
    },
    methods: {
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот тег?')) {
                this.$inertia.post(`/admin/tags/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот тег?')) {
                this.$inertia.delete(`/admin/tags/${id}`);
            }
        }
    }
}
</script>