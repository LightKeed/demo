<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="showForm" @cancel="close">
        <PageTitle>{{ category.deleted_at ? 'Просмотр удаленного' : action === 'store' ? 'Создание' : 'Редактирование' }} подраздела</PageTitle>

        <IconBtn @click="close" name="x" title="Закрыть" variation="empty" class="!absolute !top-4 !right-4"/>

        <RecordInfo
            :item="category"
            :other="[
                {icon: 'category-2', title: 'Родительская категория', value: parent_name}
            ]"
        />

        <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>

        <form @submit.prevent="action === 'store' ? store() : update()">
            <PageGroup class="mb-2">
                <FormTextInput :disabled="category.deleted_at" v-model="form.name" v-model:error="form.errors.name" label="Название раздела" placeholder="Введите название" id="name-form" required autofocus/>
                <FormTextInput :disabled="category.deleted_at" v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug-form"/>
                <FormTextInput :disabled="category.deleted_at" v-model="form.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative-form"/>
                <FormTextInput :disabled="category.deleted_at" v-model="form.sort_order" label="Сортировка" placeholder="Введите номер порядка" id="sort_order-form" type="number" min="1" max="127" required/>
            </PageGroup>
            <FormSwitchInput v-if="hasAccess('category_hiding')" :disabled="category.deleted_at" v-model="form.enabled" label="Отображать раздел" id="enabled" class="mb-4"/>

            <PageGroupBtn class="mb-4">
                <MainBtn @click="close" type="button" variation="second">Закрыть</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('category_delete')" @click="destroy(this.form.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="(hasAccess('category_create') || hasAccess('category_edit')) && !category.deleted_at" type="submit" :loading="form.processing">{{ action === 'store' ? 'Создать' : 'Сохранить' }}</MainBtn>
                    <MainBtn v-else-if="hasAccess('category_restore')" @click="restore(this.form.id)" type="button" variation="other">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
        <PageCategoryArticleList v-if="action === 'update'" :articles="category.articles"/>
    </ModalWindow>
</template>

<script>
export default {
    data() {
        return {
            showForm: false,
            action: 'store',
            parent_name: '',
            category: null,
            form: this.$inertia.form({
                id: null,
                name: '',
                slug: '',
                slug_alternative: '',
                parent_id: null,
                background_id: null,
                sort_order: 1,
                enabled: 1
            })
        }
    },
    methods: {
        show(parent, category = []) {
            this.action = !category.name ? 'store' : 'update';
            this.category = category ?? null;

            this.form.id = category.id ?? null;
            this.form.name = category.name ?? '';
            this.form.slug = category.slug ?? '';
            this.form.slug_alternative = category.slug_alternative ?? '';
            this.form.parent_id = parent.id;
            this.form.background_id = category.background_id;
            this.form.sort_order = category.sort_order ?? 1;
            this.form.enabled = category.enabled ?? 1;

            this.parent_name = parent.name ?? '';
            this.showForm = true;
        },
        close() {
            this.form.reset();
            this.showForm = false;
        },
        store() {
            this.form.post('/admin/categories', {
                onSuccess: (res) => {
                    this.close();
                },
            });
        },
        update() {
            this.form.put(`/admin/categories/${this.form.id}`, {
                onSuccess: (res) => {
                    this.close();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот раздел?')) {
                this.$inertia.post(`/admin/categories/${id}/restore`, {}, {
                    onSuccess: () => {
                        this.close();
                    }
                });
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот раздел?')) {
                this.$inertia.delete(`/admin/categories/${id}`, {
                    onSuccess: () => {
                        this.close();
                    }
                });
            }
        }
    }
}
</script>