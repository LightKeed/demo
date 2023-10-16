<template>
    <template v-if="hasAccess('slider_create')">
        <MainBtn v-if="!showForm" @click="toggleForm" class="mb-4">Добавить слайд</MainBtn>
        <div v-if="showForm">
            <PageTitle level="2">Создание слайда</PageTitle>
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
                <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
            </PageGroupBtn>
        </div>
    </template>
</template>

<script>
export default {
    props: {
        slider: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                title: '',
                description: '',
                text_button: '',
                link_button: '',
                sort_order: 0,
                media_id: null,
                slider_id: this.slider.id,
                enabled: false
            })
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post(`/admin/sliders/${this.slider.id}/slide`, {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        }
    }
}
</script>