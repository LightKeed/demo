<template>
    <Head title="Создание новости"/>
    <PageBlock>
        <PageTitle>Создание новости</PageTitle>
        <Alert/>

        <PageGroup>
            <FormMediaInput v-model="form.poster_id" v-model:error="form.errors.poster_id" label="Превью" type="image" mode="single" preview="true"/>
            <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>
        </PageGroup>

        <EditorTemplate>
            <PageTitle>Информация</PageTitle>
            <div class="mb-4">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug"/>
                <FormTextInput v-model="form.slug_alternative" v-model:error="form.errors.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative"/>
                <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description"/>
                <FormMultipleSelectInput v-model="form.tags" v-model:error="form.errors.positions" :options="tags" label="Теги" itemLabel="name" track="id" id="tags"/>
                <FormSelectInput v-model="form.section_id" v-model:error="form.errors.section_id" label="Тематический раздел" id="section_id">
                    <option :value="null">Не выбрано</option>
                    <option v-for="section in sections" :key="section" :value="section.id">
                        {{ section.name }}
                        <template v-if="section.parent">
                            ({{ section.parent.name }})
                        </template>
                    </option>
                </FormSelectInput>
            </div>
            <FormSwitchInput v-model="form.enabled" label="Отображать новость" id="enabled" class="mb-4"/>

            <PageGroupBtn>
                <MainBtn to="/admin/news" variation="second">Вернуться назад</MainBtn>
                <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        tags: Object,
        sections: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                title: '',
                slug: '',
                slug_alternative: '',
                tags: [],
                poster_id: null,
                background_id: null,
                description: '',
                enabled: 1,
                section_id: null
            })
        }
    },
    mounted() {
        editorStore().data = [];
    },
    methods: {
        store() {
            this.form.transform((data) => ({
                ...data,
                text: JSON.stringify(editorStore().data),
            }))
                .post('/admin/news');
        }
    }
}
</script>