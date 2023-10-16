<template>
    <Head :title="`${comment.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} комментария`"/>
    <PageBlock>
        <PageTitle>{{ comment.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} комментария</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="comment"/>

        <div class="flex items-center mb-4 gap-2">
            <div class="flex items-center gap-1 font-medium">
                <Icon name="box-model-2" size="5" class="flex-none"/>
                Раздел:
            </div>
            <span v-if="!comment.element">Привязка не найдена</span>
            <a v-else-if="comment.element.commentable_type === 'Новость'" :href="`/admin/news/${comment.element.commentable_id}/edit`" target="_blank" class="font-bold text-indigo-400 hover:text-indigo-600 transition">
                {{ comment.element.commentable_type }}
            </a>
            <a v-else-if="comment.element.commentable_type === 'Мероприятие'" :href="`/admin/events/${comment.element.commentable_id}/edit`" target="_blank" class="font-bold text-indigo-400 hover:text-indigo-600 transition">
                {{ comment.element.commentable_type }}
            </a>
        </div>
        <div v-if="comment.parent" class="flex items-center mb-4 gap-2">
            <div class="flex items-center gap-1 font-medium">
                <Icon name="messages" size="5" class="flex-none"/>
                Ответ на:
            </div>
            <Link :href="`/admin/comments/${comment.parent.id}/edit`" class="font-bold text-indigo-400 hover:text-indigo-600 transition">комментарий ({{ comment.parent.id }})</Link>
        </div>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormSelectInput v-model="form.status" v-model:error="form.errors.status" label="Статус" id="status" :disabled="comment.deleted_at">
                    <option value="0">На рассмотрении</option>
                    <option value="1">Опубликован</option>
                </FormSelectInput>
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Имя" placeholder="Введите имя" id="name" required :disabled="comment.deleted_at"/>
                <FormTextInput v-model="form.email" v-model:error="form.errors.email" label="Email" placeholder="Введите email" id="email" type="email" :disabled="comment.deleted_at"/>
                <FormTextInput v-model="form.phone" v-model:error="form.errors.phone" label="Контактный номер" placeholder="Введите контактный номер" id="phone" :disabled="comment.deleted_at"/>
                <FormTextInput v-model="form.signature" v-model:error="form.errors.signature" label="Подпись" placeholder="Введите подпись" id="signature" :disabled="comment.deleted_at"/>
                <FormTextInput v-model="form.other" v-model:error="form.errors.other" label="Другое" placeholder="Введите другое" id="other" :disabled="comment.deleted_at"/>
            </PageGroup>
            <FormTextareaInput v-model="form.text" v-model:error="form.errors.text" label="Комментарий" placeholder="Введите комментарий" id="comment" :disabled="comment.deleted_at" class="mb-4"/>

            <PageGroupBtn>
                <MainBtn :to="comment.deleted_at ? '/admin/comments?archive=1' : '/admin/comments'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('comment_delete')" @click="destroy(comment.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!comment.deleted_at" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('comment_restore')" @click="restore(comment.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </PageBlock>
</template>

<script>
export default {
    props: {
        comment: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                status: this.comment.status,
                name: this.comment.name,
                email: this.comment.email,
                phone: this.comment.phone,
                signature: this.comment.signature,
                other: this.comment.other,
                text: this.comment.text
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/comments/${this.comment.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        status: this.comment.status,
                        name: this.comment.name,
                        email: this.comment.email,
                        phone: this.comment.phone,
                        signature: this.comment.signature,
                        other: this.comment.other,
                        text: this.comment.text
                    });

                    this.form.reset();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот комментарий?')) {
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