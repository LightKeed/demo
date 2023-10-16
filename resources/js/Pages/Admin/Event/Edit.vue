<template>
    <Head :title="`${event.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} мероприятия`"/>
    <PageBlock>
        <PageTitle>{{ event.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} мероприятия</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="event"/>

        <PageGroup>
            <FormMediaInput v-model="form.poster_id" v-model:error="form.errors.poster_id" label="Превью" type="image" mode="single" preview="true"/>
            <FormMediaInput v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>
        </PageGroup>

        <EditorTemplate>
            <PageTitle>Информация</PageTitle>
            <div class="mb-4">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required :disabled="event.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="event.deleted_at"/>
                <FormTextInput v-model="form.slug_alternative" v-model:error="form.errors.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative" :disabled="event.deleted_at"/>
                <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description" :disabled="event.deleted_at"/>
                <FormMultipleSelectInput v-model="form.tags" v-model:error="form.errors.positions" :options="tags" label="Теги" itemLabel="name" track="id" id="tags" :disabled="!!event.deleted_at"/>
                <FormTextInput v-model="form.event_start_at" v-model:error="form.errors.event_start_at" label="Дата начала" type="datetime-local" id="event_start_at" :disabled="event.deleted_at"/>
                <FormTextInput v-model="form.event_end_at" v-model:error="form.errors.event_end_at" label="Дата завершения" type="datetime-local" id="event_end_at" :disabled="event.deleted_at"/>
                <template v-if="form.recording">
                    <FormTextInput v-model="form.record_start_at" v-model:error="form.errors.record_start_at" label="Дата начала записи" type="datetime-local" id="record_start_at" :disabled="event.deleted_at"/>
                    <FormTextInput v-model="form.record_end_at" v-model:error="form.errors.record_end_at" label="Дата завершения записи" type="datetime-local" id="record_end_at" :disabled="event.deleted_at"/>
                </template>
            </div>
            <FormSwitchInput v-if="hasAccess('event_hiding')" v-model="form.enabled" label="Отображать мероприятие" id="enabled" class="mb-4" :disabled="event.deleted_at"/>
            <FormSwitchInput v-model="form.recording" label="Разрешить запись" id="recording" class="mb-4" :disabled="event.deleted_at"/>

            <PageGroupBtn>
                <MainBtn :to="event.deleted_at ? '/admin/events?articles=1' : '/admin/events'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('event_delete')" @click="destroy(event.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!event.deleted_at" @click="update" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('event_restore')" @click="restore(event.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        event: Object,
        tags: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.event.id,
                title: this.event.title,
                slug: this.event.slug,
                slug_alternative: this.event.slug_alternative,
                tags: this.event.tags,
                poster_id: this.event.poster_id,
                background_id: this.event.background_id,
                description: this.event.description,
                enabled: this.event.enabled,
                recording: this.event.recording,
                event_start_at: this.event.event_start_at,
                event_end_at: this.event.event_end_at,
                record_start_at: this.event.record_start_at,
                record_end_at: this.event.record_end_at
            })
        }
    },
    mounted() {
        editorStore().data = JSON.parse(this.event.text) ?? [];
    },
    methods: {
        update() {
            this.form.transform((data) => ({
                ...data,
                text: JSON.stringify(editorStore().data),
                record_start_at: this.form.recording ? this.form.record_start_at : null,
                record_end_at: this.form.recording ? this.form.record_end_at : null
            }))
                .put(`/admin/events/${this.form.id}`, {
                    onSuccess: (res) => {
                        this.form.slug = this.event.slug;
                        this.form.slug_alternative = this.event.slug_alternative;
                        this.form.tags = this.event.tags;
                        this.form.event_start_at = this.event.event_start_at;
                        this.form.event_end_at = this.event.event_end_at;
                        this.form.record_start_at = this.event.record_start_at;
                        this.form.record_end_at = this.event.record_end_at;
                    },
                });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить это мероприятие?')) {
                this.$inertia.post(`/admin/events/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это мероприятие?')) {
                this.$inertia.delete(`/admin/events/${id}`);
            }
        }
    }
}
</script>