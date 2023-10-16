<template>
    <Head title="Создание страницы"/>
    <PageBlock>
        <PageTitle>Создание страницы</PageTitle>
        <Alert/>

        <PageArticleSelectCategory v-model="form.category_id" :categories="categories"/>

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
                <FormTextInput v-model="form.sort_order" label="Сортировка" placeholder="Введите номер порядка" id="sort_order" type="number" min="1" max="127" required/>
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
            <FormSwitchInput v-model="form.enabled" label="Отображать страницу" id="enabled" class="mb-4"/>
            <FormSwitchInput v-model="form.enabled_menu" label="Отображать в меню" id="enabled_menu" class="mb-4"/>

            <PageGroupBtn>
                <MainBtn to="/admin/articles" variation="second">Вернуться назад</MainBtn>
                <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
            </PageGroupBtn>
        </EditorTemplate>
    </PageBlock>
</template>

<script>
import { useStoreEditor as editorStore } from "@/Stores/editor.js";

export default {
    props: {
        categories: Object,
        sliders: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                title: '',
                slug: '',
                slug_alternative: '',
                category_id: null,
                poster_id: null,
                background_id: null,
                description: '',
                sort_order: 1,
                enabled: 1,
                enabled_menu: 1,
                slider_id: null
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
                .post('/admin/articles');
        }
    }
}
</script>