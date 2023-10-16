<template>
    <Head :title="filters.archive ? 'Тематические разделы - Архив' : 'Тематические разделы'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Тематические разделы - Архив' : 'Тематические разделы' }}
            <template v-if="hasAccess('thematic-section_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/thematic-sections?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/thematic-sections`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <template v-if="hasAccess('thematic-section_create')">
            <MainBtn v-if="!filters.archive && !showForm" @click="toggleForm" class="mb-4">Создать раздел</MainBtn>
            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание тематического раздела</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название тега" placeholder="Введите название" id="name" required/>
                    <FormTextInput v-model="form.slug" v-model:error="form.errors.slug" label="URL" placeholder="Введите URL" desc="Генерируется автоматически, если оставить поле пустым" id="slug"/>
                    <FormTextInput v-model="form.rating" v-model:error="form.errors.rating" label="Рейтинг" placeholder="Введите значение рейтинга" type="number" min="0" id="rating"/>
                    <FormSelectInput v-model="form.parent_id" v-model:error="form.errors.parent_id" label="Родительский раздел" id="parent_id">
                        <option :value="null">Не выбрано</option>
                        <option v-for="parent in parents" :key="parent" :value="parent.id">{{ parent.name }}</option>
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
            <FormSelectInput v-model="formSearch.parent" label="Родительский раздел" id="search-parent">
                <option :value="null">Не выбрано</option>
                <option value="empty">Без родительского раздела</option>
                <option v-for="parent in parents" :key="parent" :value="parent.id">{{ parent.name }}</option>
            </FormSelectInput>
        </PageGroup>
        <PageThematicSectionList :sections="sections.data"/>
        <Pagination :links="sections.links" :total="sections.total" :length="sections.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        sections: Object,
        parents: Object,
        articles: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: '',
                slug: '',
                rating: '',
                parent_id: null
            }),
            formSearch: {
                title: this.filters.title,
                parent: this.filters.parent,
                archive: this.filters.archive
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/thematic-sections', {
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
                this.$inertia.get('/admin/thematic-sections', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>