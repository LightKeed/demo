<template>
    <Head :title="filters.archive ? 'Разделы - Архив' : 'Разделы'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Разделы - Архив' : 'Разделы' }}
            <template v-if="hasAccess('category_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/categories?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/categories`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <template v-if="hasAccess('category_create')">
            <MainBtn v-if="!filters.archive && !showForm" @click="toggleForm" class="mb-4">Создать раздел</MainBtn>

            <FormMediaInput v-if="showForm" v-model="form.background_id" v-model:error="form.errors.background_id" label="Главный фон" type="image" mode="single" preview="true"/>

            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание основного раздела</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название раздела" placeholder="Введите название" id="name" required/>
                    <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug"/>
                    <FormTextInput v-model="form.slug_alternative" label="Альтернативный URL" placeholder="Введите альтернативный URL" id="slug_alternative"/>
                    <FormTextInput v-model="form.sort_order" label="Сортировка" placeholder="Введите номер порядка" id="sort_order" type="number" min="1" max="127" required/>
                </PageGroup>
                <FormSwitchInput v-model="form.enabled" label="Отображать раздел" id="enabled" class="mb-4"/>
                <PageGroupBtn class="mb-4">
                    <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
                </PageGroupBtn>
            </form>
        </template>
        <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
        <PageCategoryGridCard :categories="categories"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        categories: Object,
        reserved_slug: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: '',
                slug: '',
                slug_alternative: '',
                parent_id: null,
                background_id: null,
                sort_order: 1,
                enabled: 1
            }),
            formSearch: {
                title: this.filters.title,
                archive: this.filters.archive
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/categories', {
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
                this.$inertia.get('/admin/categories', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>