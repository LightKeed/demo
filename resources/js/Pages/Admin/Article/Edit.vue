<template>
    <Head :title="`${article.deleted_at ? 'Просмотр удаленной ' : 'Редактирование'} страницы`"/>
    <PageBlock>
        <PageTitle>{{ article.deleted_at ? 'Просмотр удаленной ' : 'Редактирование' }} страницы</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="article"/>

        <PageArticleSelectCategory v-model="form.category_id" :categories="categories" :disabled="article.deleted_at"/>

        <PageGroup>
            <FormMediaInput v-model="form.poster_id" v-model:error="form.errors.poster_id" label="Превью" type="image" mode="single" preview="true"/>
            <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>
        </PageGroup>

        <EditorTemplate>
            <PageTitle>Информация</PageTitle>
            <div class="mb-4">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required :disabled="article.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="article.deleted_at"/>
                <FormTextInput v-model="form.slug_alternative" v-model:error="form.errors.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative" :disabled="article.deleted_at"/>
                <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description" :disabled="article.deleted_at"/>
                <FormTextInput v-model="form.sort_order" label="Сортировка" placeholder="Введите номер порядка" id="sort_order" type="number" min="1" max="127" required :disabled="article.deleted_at"/>
                <FormSelectInput v-model="form.slider_id" v-model:error="form.errors.slider_id" label="Слайдер" desc="Если оставить поле пустым будет отображаться главный фон" id="slider_id">
                    <option :value="null">Не выбрано</option>
                    <option
                        v-for="slider in sliders"
                        :key="slider"
                        :value="slider.id"
                    >
                        {{ slider.name }} (ID: {{ slider.id }})
                    </option>
                </FormSelectInput>
            </div>
            <FormSwitchInput v-if="hasAccess('article_hiding')" v-model="form.enabled" label="Отображать страницу" id="enabled" class="mb-4" :disabled="article.deleted_at"/>
            <FormSwitchInput v-model="form.enabled_menu" label="Отображать в меню" id="enabled_menu" class="mb-4" :disabled="article.deleted_at"/>

            <PageGroupBtn>
                <MainBtn :href="article.deleted_at ? '/admin/tags?articles=1' : '/admin/articles'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('article_delete')" @click="destroy(article.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!article.deleted_at" @click="update" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('article_restore')" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        article: Object,
        categories: Object,
        sliders: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.article.id,
                title: this.article.title,
                slug: this.article.slug,
                slug_alternative: this.article.slug_alternative,
                category_id: this.article.category_id,
                poster_id: this.article.poster_id,
                background_id: this.article.background_id,
                description: this.article.description,
                sort_order: this.article.sort_order,
                enabled: this.article.enabled,
                enabled_menu: this.article.enabled_menu,
                slider_id: this.article.slider_id
            })
        }
    },
    mounted() {
        editorStore().data = [];
        editorStore().data = JSON.parse(this.article.text) ?? [];
    },
    methods: {
        update() {
            this.form.transform((data) => ({
                ...data,
                text: JSON.stringify(editorStore().data),
            }))
                .put(`/admin/articles/${this.form.id}`, {
                    onSuccess: () => {
                        this.form.slug = this.article.slug;
                        this.form.slug_alternative = this.article.slug_alternative;
                    },
                });
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