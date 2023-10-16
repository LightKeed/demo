<template>
    <Head title="Создание мероприятия"/>
    <PageBlock>
        <PageTitle>Создание мероприятия</PageTitle>
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
                <FormTextInput v-model="form.event_start_at" v-model:error="form.errors.event_start_at" label="Дата начала" type="datetime-local" id="event_start_at"/>
                <FormTextInput v-model="form.event_end_at" v-model:error="form.errors.event_end_at" label="Дата завершения" type="datetime-local" id="event_end_at"/>
                <template v-if="form.recording">
                    <FormTextInput v-model="form.record_start_at" v-model:error="form.errors.record_start_at" label="Дата начала записи" type="datetime-local" id="record_start_at"/>
                    <FormTextInput v-model="form.record_end_at" v-model:error="form.errors.record_end_at" label="Дата завершения записи" type="datetime-local" id="record_end_at"/>
                </template>
            </div>
            <FormSwitchInput v-model="form.enabled" label="Отображать мероприятие" id="enabled" class="mb-4"/>
            <FormSwitchInput v-model="form.recording" label="Разрешить запись" id="recording" class="mb-4"/>

            <PageGroupBtn>
                <MainBtn to="/admin/events" variation="second">Вернуться назад</MainBtn>
                <MainBtn type="submit" @click="store" :loading="form.processing">Создать</MainBtn>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        tags: Object
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
                recording: 0,
                event_start_at: null,
                event_end_at: null,
                record_start_at: null,
                record_end_at: null
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
                record_start_at: this.form.recording ? this.form.record_start_at : null,
                record_end_at: this.form.recording ? this.form.record_end_at : null
            }))
                .post('/admin/events');
        }
    }
}
</script>