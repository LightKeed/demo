<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="news.length">
            <PageListItem
                v-for="item in news" :key="item"
                :deleted="item.deleted_at"
                @dblclick="hasAccessModel('news', item.id) ? this.$inertia.get(`/admin/news/${item.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="news"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ item.title }}</span>
                            <span class="text-xs">({{ item.slug }})</span>
                        </div>
                        <div v-if="item.tags.length" class="flex flex-wrap items-center gap-2 mb-2">
                            <span
                                v-for="tag in item.tags"
                                :key="tag"
                                class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5"
                            >
                                {{ tag.name }}
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(item.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="eye-check" size="5"/>
                                {{ item.views }}
                                <Tooltip title="Просмотров"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="section" size="5"/>
                                {{ item.section ? item.section.name : 'Без раздела' }}
                                <Tooltip title="Тематический раздел"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <template v-if="hasAccess('news_hiding') && !item.deleted_at">
                        <IconBtn v-if="item.enabled" @click="toggleVisible(item.id, 'hide')" name="eye" size="5" title="Скрыть"/>
                        <IconBtn v-else @click="toggleVisible(item.id, 'show')" name="eye-off" size="5" variation="second" title="Показать"/>
                    </template>
                    <IconBtn v-if="hasAccess('news_restore') && item.deleted_at" @click="restore(item.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccessModel('news', item.id)">
                        <IconBtn v-if="!item.deleted_at" :to="`/admin/news/${item.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/news/${item.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-if="hasAccess('news_delete')" @click="destroy(item.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="news"/>
            Новости не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        news: Object
    },
    methods: {
        async toggleVisible(id, action) {
            if(await this.$refs.modalConfirm.show(null, `Вы действительно хотите ${action === 'hide' ? 'скрыть' : 'показать'} эту новость?`)) {
                this.$inertia.post(`/admin/news/${id}/visible`);
            }
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить эту новость?')) {
                this.$inertia.post(`/admin/news/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту новость?')) {
                this.$inertia.delete(`/admin/news/${id}`);
            }
        }
    }
}
</script>