<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="comments.length">
            <PageListItem
                v-for="comment in comments"
                :key="comment"
                :deleted="comment.deleted_at"
                @dblclick="hasAccess('comment_edit') ? this.$inertia.get(`/admin/comments/${comment.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="message"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">{{ comment.name }}</span>
                        <div class="flex items-center gap-2 mb-1">
                            Статус:
                            <span v-if="comment.status === 0" class="text-xs bg-red-500 text-white rounded-full px-2 py-0.5">
                                На рассмотрении
                            </span>
                            <span v-else-if="comment.status === 1" class="text-xs bg-green-500 text-white rounded-full px-2 py-0.5">
                                Опубликован
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="box-model-2" size="5"/>
                                <span v-if="!comment.element">
                                    Привязка не найдена
                                </span>
                                <a v-else-if="comment.element.commentable_type === 'Новость'" :href="`/admin/news/${comment.element.commentable_id}/edit`" target="_blank" class="font-bold text-indigo-400 hover:text-indigo-600 transition">
                                    {{ comment.element.commentable_type }}
                                </a>
                                <a v-else-if="comment.element.commentable_type === 'Мероприятие'" :href="`/admin/events/${comment.element.commentable_id}/edit`" target="_blank" class="font-bold text-indigo-400 hover:text-indigo-600 transition">
                                    {{ comment.element.commentable_type }}
                                </a>
                                <Tooltip title="Раздел"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(comment.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="mail" size="5"/>
                                {{ comment.email ?? 'Не указан' }}
                                <Tooltip title="Email"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="phone" size="5"/>
                                {{ comment.phone ?? 'Не указан' }}
                                <Tooltip title="Телефон"/>
                            </div>
                            <div v-if="comment.parent" class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="messages" size="5"/>
                                <a :href="`/admin/comments/${comment.parent.id}/edit`" target="_blank" class="font-bold text-indigo-400 hover:text-indigo-600 transition">
                                    Комментарий ({{ comment.parent.id }})
                                </a>
                                <Tooltip title="Ответ на"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="hasAccess('comment_restore') && comment.deleted_at" @click="restore(comment.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccess('comment_edit')">
                        <IconBtn v-if="!comment.deleted_at" :to="`/admin/comments/${comment.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/comments/${comment.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-if="hasAccess('comment_delete')" @click="destroy(comment.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="message"/>
            Комментарии не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        comments: Object
    },
    methods: {
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите удалить этот комментарий?')) {
                this.$inertia.post(`/admin/comments/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот комментарий?')) {
                this.$inertia.delete(`/admin/comments/${id}`);
            }
        }
    }
}
</script>