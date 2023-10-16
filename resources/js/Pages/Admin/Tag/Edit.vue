<template>
    <Head :title="`${tag.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} тега`"/>
    <PageBlock>
        <PageTitle>{{ tag.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} тега</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="tag"/>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название тега" placeholder="Введите название" id="name" required :disabled="tag.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="tag.deleted_at"/>
                <FormTextInput v-model.number="form.rating" v-model:error="form.errors.rating" label="Рейтинг" placeholder="Введите значение рейтинга" type="number" min="0" id="rating" :disabled="tag.deleted_at"/>
                <FormSelectInput v-model="form.scope" v-model:error="form.errors.scope" label="Область действия" id="scope" :disabled="tag.deleted_at">
                    <option :value="null">Не выбрано</option>
                    <option value="news">Новости</option>
                    <option value="events">Мероприятия</option>
                </FormSelectInput>
            </PageGroup>
            
            <PageGroupBtn>
                <MainBtn :to="tag.deleted_at ? '/admin/tags?archive=1' : '/admin/tags'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('tag_delete')" @click="destroy(tag.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!tag.deleted_at" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('tag_restore')" @click="restore(tag.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </PageBlock>
</template>

<script>
export default {
    props: {
        tag: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.tag.id,
                name: this.tag.name,
                slug: this.tag.slug,
                rating: this.tag.rating ?? 0,
                scope: this.tag.scope
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/tags/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.tag.id,
                        name: this.tag.name,
                        slug: this.tag.slug,
                        rating: this.tag.rating ?? 0,
                        scope: this.tag.scope
                    });

                    this.form.reset();
                },
            });
        },
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