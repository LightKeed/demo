<template>
    <Head :title="filters.archive ? 'Теги - Архив' : 'Теги'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Теги - Архив' : 'Теги' }}
            <template v-if="hasAccess('tag_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/tags?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/tags`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <template v-if="hasAccess('tag_create')">
            <MainBtn v-if="!filters.archive && !showForm" @click="toggleForm" class="mb-4">Создать тег</MainBtn>
            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание тега</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название тега" placeholder="Введите название" id="name" required/>
                    <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug"/>
                    <FormTextInput v-model="form.rating" v-model:error="form.errors.rating" label="Рейтинг" placeholder="Введите значение рейтинга" type="number" min="0" id="rating"/>
                    <FormSelectInput v-model="form.scope" v-model:error="form.errors.scope" label="Область действия" id="scope">
                        <option :value="null">Не выбрано</option>
                        <option value="news">Новости</option>
                        <option value="events">Мероприятия</option>
                    </FormSelectInput>
                </PageGroup>
                <PageGroupBtn class="mb-4">
                    <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
                </PageGroupBtn>
            </form>
        </template>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.scope" label="Область действия" id="search-scope">
                <option :value="null">Все области</option>
                <option value="empty">Не указана</option>
                <option value="news">Новости</option>
                <option value="events">Мероприятия</option>
            </FormSelectInput>
        </PageGroup>
        <PageTagList :tags="tags.data"/>
        <Pagination :links="tags.links" :total="tags.total" :length="tags.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        tags: Object,
        articles: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: '',
                slug: '',
                rating: '',
                scope: null
            }),
            formSearch: {
                title: this.filters.title,
                scope: this.filters.scope,
                archive: this.filters.archive
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/tags', {
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
                this.$inertia.get('/admin/tags', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>