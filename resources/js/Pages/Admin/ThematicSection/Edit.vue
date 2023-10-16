<template>
    <Head :title="`${section.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} тематического раздела`"/>
    <PageBlock>
        <PageTitle>{{ section.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} тематического раздела</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="section"/>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название раздела" placeholder="Введите название" id="name" required :disabled="section.deleted_at"/>
                <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug" :disabled="section.deleted_at"/>
                <FormTextInput v-model.number="form.rating" v-model:error="form.errors.rating" label="Рейтинг" placeholder="Введите значение рейтинга" type="number" min="0" id="rating" :disabled="section.deleted_at"/>
                <FormSelectInput v-model="form.parent_id" v-model:error="form.errors.parent_id" label="Родительский раздел" id="parent_id" :disabled="section.deleted_at">
                    <option :value="null">Не выбрано</option>
                    <option v-for="parent in parents" :key="parent" :value="parent.id">{{ parent.name }}</option>
                </FormSelectInput>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn :to="section.deleted_at ? '/admin/thematic-sections?archive=1' : '/admin/thematic-sections'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('thematic-section_delete')" @click="destroy(section.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!section.deleted_at" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="hasAccess('thematic-section_restore')" @click="restore(section.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </PageBlock>
</template>

<script>
export default {
    props: {
        section: Object,
        parents: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.section.id,
                name: this.section.name,
                slug: this.section.slug,
                rating: this.section.rating ?? 0,
                parent_id: this.section.parent_id
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/thematic-sections/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.section.id,
                        name: this.section.name,
                        slug: this.section.slug,
                        rating: this.section.rating ?? 0,
                        parent_id: this.section.parent_id
                    });

                    this.form.reset();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот тематический раздел?')) {
                this.$inertia.post(`/admin/thematic-sections/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот тематический раздел?')) {
                this.$inertia.delete(`/admin/thematic-sections/${id}`);
            }
        }
    }
}
</script>