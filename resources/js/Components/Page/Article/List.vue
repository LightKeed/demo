<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="articles.length">
            <PageListItem
                v-for="article in articles" :key="article"
                :deleted="article.deleted_at"
                @dblclick="hasAccessModel('article', article.id) ? changeLocation(`/admin/articles/${article.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="file"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ article.title }}</span>
                            <span class="text-xs">({{ article.slug }})</span>
                        </div>
                        <div v-if="article.description" class="text-xs text-gray-600">{{ article.description }}</div>
                        <div class="flex items-center gap-2">
                            Категория:
                            <Link
                                v-if="article.category"
                                :href="`/admin/categories/${article.category.id}/edit`"
                                class="block text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5 hover:scale-[1.02] transition"
                            >
                                {{ article.category.name }}
                            </Link>
                            <span v-else class="block text-xs bg-gray-600 text-white rounded-full px-2 py-1 cursor-default">Без категории</span>
                        </div>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(article.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания" position="top"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <template v-if="hasAccess('article_hiding') && !article.deleted_at">
                        <IconBtn v-if="article.enabled" @click="toggleVisible(article.id, 'hide')" name="eye" size="5" title="Скрыть"/>
                        <IconBtn v-else @click="toggleVisible(article.id, 'show')" name="eye-off" size="5" variation="second" title="Показать"/>
                    </template>
                    <IconBtn v-if="hasAccess('article_restore') && article.deleted_at" @click="restore(article.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccessModel('article', article.id)">
                        <IconBtn v-if="!article.deleted_at" :href="`/admin/articles/${article.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/articles/${article.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-else :to="`/admin/articles/${article.id}/edit`" name="world" size="5" variation="other" title="Перейти"/>
                    <IconBtn v-if="hasAccess('article_delete')" @click="destroy(article.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="file"/>
            Страницы не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        articles: Object
    },
    methods: {
        changeLocation(href) {
            window.location.replace(window.location.origin + href);
        },
        async toggleVisible(id, action) {
            if(await this.$refs.modalConfirm.show(null, `Вы действительно хотите ${action === 'hide' ? 'скрыть' : 'показать'} эту страницу?`)) {
                this.$inertia.post(`/admin/articles/${id}/visible`);
            }
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить эту страницу?')) {
                this.$inertia.post(`/admin/articles/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту страницу?')) {
                this.$inertia.delete(`/admin/articles/${id}`);
            }
        }
    }
}
</script>