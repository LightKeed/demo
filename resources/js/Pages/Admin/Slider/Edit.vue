<template>
    <Head title="Редактирование слайдера"/>
    <PageBlock>
        <PageTitle>Редактирование слайдера</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="slider"/>

        <form @submit.prevent="update" class="mb-6">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название слайдера" placeholder="Введите название" id="name" required/>
                <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description"/>
            </PageGroup>

            <FormSwitchInput v-model="form.enabled" label="Отображать слайдер" id="enabled" class="mb-4"/>
            <FormSwitchInput v-model="form.can_removed" label="Можно удалить" id="can_removed" class="mb-4"/>

            <PageGroupBtn>
                <MainBtn to="/admin/sliders" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="hasAccess('slider_delete')" @click="destroy(slider.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>

        <PageSliderSlideList :slider="slider" :slides="slider.slides"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        slider: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.slider.name,
                description: this.slider.description,
                enabled: this.slider.enabled,
                can_removed: this.slider.can_removed
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/sliders/${this.slider.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        name: this.slider.name,
                        description: this.slider.description,
                        enabled: this.slider.enabled,
                        can_removed: this.slider.can_removed
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот слайдер?')) {
                this.$inertia.delete(`/admin/sliders/${id}`);
            }
        }
    }
}
</script>