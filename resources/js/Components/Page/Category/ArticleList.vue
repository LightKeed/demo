<template>
    <PageTitle>Страницы</PageTitle>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="articles.length">
            <PageListItem
                v-for="article in articles" :key="article"
                @dblclick="hasAccess('article_edit') ? this.$inertia.get(`/admin/articles/${article.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="file"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ article.title }}</span>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(article.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('article_edit')" :to="`/admin/articles/${article.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
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
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту страницу?')) {
                this.$inertia.delete(`/admin/articles/${id}`);
            }
        }
    }
}
</script>