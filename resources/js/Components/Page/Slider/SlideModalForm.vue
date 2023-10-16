<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="showModal" @cancel="close">
        <PageTitle>Редактирование слайда</PageTitle>

        <IconBtn @click="close" name="x" title="Закрыть" variation="empty" class="!absolute !top-4 !right-4"/>

        <RecordInfo :item="slide"/>

        <PageGroup class="mb-2">
            <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название слайда" placeholder="Введите название" id="name" required/>
            <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description"/>
            <FormTextInput v-model="form.text_button" v-model:error="form.errors.text_button" label="Текст кнопки" placeholder="Введите текст кнопки" id="text_button"/>
            <FormTextInput v-model="form.link_button" v-model:error="form.errors.link_button" label="URL кнопки" placeholder="Введите url кнопки" id="link_button"/>
            <FormTextInput v-model="form.sort_order" v-model:error="form.errors.sort_order" label="Сортировка" placeholder="Введите номер порядка" type="number" min="0" max="255" id="sort_order"/>
        </PageGroup>

        <FormSwitchInput v-model="form.enabled" label="Отображать слайд" id="enabled" class="mb-4"/>
        <FormMediaInput v-model="form.media_id" v-model:error="form.errors.media_id" label="Изображение" type="image" mode="single" preview="true"/>

        <PageGroupBtn class="mb-4">
            <MainBtn @click="close" type="button" variation="second">Закрыть</MainBtn>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <MainBtn v-if="hasAccess('slider_delete')" @click="destroy(slide.id)" variation="danger" type="button">Удалить</MainBtn>
                <MainBtn v-if="hasAccess('slider_edit')" @click="update" :loading="form.processing">Сохранить</MainBtn>
            </div>
        </PageGroupBtn>
    </ModalWindow>
</template>

<script>
export default {
    data() {
        return {
            showModal: false,
            slide: null,
            form: this.$inertia.form({
                title: '',
                description: '',
                text_button: '',
                link_button: '',
                sort_order: 0,
                media_id: null,
                slider_id: null,
                enabled: false
            })
        }
    },
    methods: {
        show(slide) {
            this.slide = slide;
            this.form.title = slide.title;
            this.form.description = slide.description;
            this.form.text_button = slide.text_button;
            this.form.link_button = slide.link_button;
            this.form.sort_order = slide.sort_order;
            this.form.media_id = slide.media_id;
            this.form.slider_id = slide.slider_id;
            this.form.enabled = slide.enabled;

            this.showModal = true;
        },
        close() {
            this.form.reset();
            this.slide = null;
            this.showModal = false;

        },
        update() {
            this.form.put(`/admin/sliders/slide/${this.slide.id}`, {
                onSuccess: (res) => {
                    this.close();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот слайд?')) {
                this.$inertia.delete(`/admin/sliders/slide/${id}`, {
                    onSuccess: (res) => {
                        this.close();
                    },
                });
            }
        }
    }
}
</script>