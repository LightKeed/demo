<template>
    <Head :title="`${news.deleted_at ? 'Просмотр удаленной ' : 'Редактирование'} новости`"/>
    <PageBlock>
        <PageTitle>{{ news.deleted_at ? 'Просмотр удаленной ' : 'Редактирование' }} новости</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="news"/>

        <PageGroup>
            <FormMediaInput v-model="form.poster_id" v-model:error="form.errors.poster_id" label="Превью" type="image" mode="single" preview="true"/>
            <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>
        </PageGroup>

        <EditorTemplate>
            <PageTitle>Информация</PageTitle>
            <div class="mb-4">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required :disabled="news.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="news.deleted_at"/>
                <FormTextInput v-model="form.slug_alternative" v-model:error="form.errors.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative" :disabled="news.deleted_at"/>
                <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description" :disabled="news.deleted_at"/>
                <FormMultipleSelectInput v-model="form.tags" v-model:error="form.errors.positions" :options="tags" label="Теги" itemLabel="name" track="id" id="tags" :disabled="news.deleted_at"/>
                <FormSelectInput v-model="form.section_id" v-model:error="form.errors.section_id" label="Тематический раздел" id="section_id" :disabled="news.deleted_at">
                    <option :value="null">Не выбрано</option>
                    <option v-for="section in sections" :key="section" :value="section.id">
                        {{ section.name }}
                        <template v-if="section.parent">
                             ({{ section.parent.name }})
                        </template>
                    </option>
                </FormSelectInput>
            </div>
            <FormSwitchInput v-if="hasAccess('news_hiding')" v-model="form.enabled" label="Отображать новость" id="enabled" class="mb-4" :disabled="news.deleted_at"/>

            <PageGroupBtn>
                <MainBtn :to="news.deleted_at ? '/admin/news?articles=1' : '/admin/news'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('news_delete')" @click="destroy(news.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!news.deleted_at" @click="update" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('news_restore')" @click="restore(news.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        news: Object,
        tags: Object,
        sections: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.news.id,
                title: this.news.title,
                slug: this.news.slug,
                slug_alternative: this.news.slug_alternative,
                tags: this.news.tags,
                poster_id: this.news.poster_id,
                background_id: this.news.background_id,
                description: this.news.description,
                enabled: this.news.enabled,
                section_id: this.news.section_id,
            })
        }
    },
    mounted() {
        editorStore().data = JSON.parse(this.news.text) ?? [];
    },
    methods: {
        update() {
            this.form.transform((data) => ({
                ...data,
                text: JSON.stringify(editorStore().data),
            }))
                .put(`/admin/news/${this.form.id}`, {
                    onSuccess: (res) => {
                        this.form.slug = this.news.slug;
                        this.form.slug_alternative = this.news.slug_alternative;
                        this.form.tags = this.news.tags;
                    },
                });
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