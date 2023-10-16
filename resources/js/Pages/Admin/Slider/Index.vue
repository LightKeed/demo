<template>
    <Head title="Слайдеры"/>
    <PageBlock>
        <PageTitle>Слайдеры</PageTitle>
        <Alert/>
        <template v-if="hasAccess('slider_create')">
            <MainBtn v-if="!showForm" @click="toggleForm" class="mb-4">Создать слайдер</MainBtn>
            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание слайдера</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название слайдера" placeholder="Введите название" id="name" required/>
                    <FormTextInput v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Введите описание" id="description"/>
                </PageGroup>

                <FormSwitchInput v-model="form.enabled" label="Отображать слайдер" id="enabled" class="mb-4"/>
                <FormSwitchInput v-model="form.can_removed" label="Можно удалить" id="can_removed" class="mb-4"/>

                <PageGroupBtn class="mb-4">
                    <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
                </PageGroupBtn>
            </form>
        </template>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.enabled" label="Отображение слайдера" id="search-enabled">
                <option :value="null">Не выбрано</option>
                <option value="0">Скрыт</option>
                <option value="1">Отображен</option>
            </FormSelectInput>
        </PageGroup>
        <PageSliderList :sliders="sliders.data"/>
        <Pagination :links="sliders.links" :total="sliders.total" :length="sliders.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        sliders: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: '',
                description: '',
                enabled: false,
                can_removed: false
            }),
            formSearch: {
                title: this.filters.title,
                enabled: this.filters.enabled
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/sliders', {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/sliders', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>