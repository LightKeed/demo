<template>
    <Head :title="`${category.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} раздела`"/>
    <PageBlock>
        <PageTitle>{{ category.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} раздела «{{ category.name }}»</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="category"/>

        <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название раздела" placeholder="Введите название" id="name" required :disabled="category.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="category.deleted_at"/>
                <FormTextInput v-model="form.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative" :disabled="category.deleted_at"/>
                <FormTextInput v-model="form.sort_order" label="Сортировка" placeholder="Введите номер порядка" id="sort_order" type="number" min="1" max="127" required :disabled="category.deleted_at"/>
            </PageGroup>
            <FormSwitchInput v-if="hasAccess('category_hiding')" v-model="form.enabled" label="Отображать раздел" id="enabled" class="mb-4" :disabled="category.deleted_at"/>

            <PageGroupBtn class="mb-4">
                <MainBtn :to="category.deleted_at ? '/admin/categories?archive=1' : '/admin/categories'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('category_delete')" @click="destroy(category.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!category.deleted_at" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('category_restore')" @click="restore(category.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
        <PageCategoryArticleList :articles="category.articles"/>
        <PageCategorySubList :category="category"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        category: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.category.id,
                name: this.category.name,
                slug: this.category.slug,
                slug_alternative: this.category.slug_alternative,
                parent_id: null,
                background_id: this.category.background_id,
                sort_order: this.category.sort_order,
                enabled: this.category.enabled
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/categories/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.category.id,
                        name: this.category.name,
                        slug: this.category.slug,
                        slug_alternative: this.category.slug_alternative,
                        parent_id: null,
                        background_id: this.category.background_id,
                        sort_order: this.category.sort_order,
                        enabled: this.category.enabled
                    });

                    this.form.reset();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот раздел?')) {
                this.$inertia.post(`/admin/categories/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот раздел?')) {
                this.$inertia.delete(`/admin/categories/${id}`);
            }
        }
    }
}
</script>