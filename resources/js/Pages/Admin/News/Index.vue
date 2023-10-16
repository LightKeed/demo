<template>
    <Head :title="filters.archive ? 'Новости - Архив' : 'Новости'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Новости - Архив' : 'Новости' }}
            <template v-if="hasAccess('news_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/news?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/news`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <MainBtn v-if="hasAccess('news_create') && !filters.archive" to="/admin/news/create" class="mb-4">Создать новость</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.date" label="Дата публикации" type="date" id="search-date"/>
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.section" label="Тематический раздел" id="search-section">
                <option :value="null">Все разделы</option>
                <option value="empty">Без раздела</option>
                <option
                    v-for="section in sections"
                    :key="section"
                    :value="section.id"
                >
                    {{ section.name }}
                    <template v-if="section.parent">
                        ({{ section.parent.name }})
                    </template>
                </option>
            </FormSelectInput>
            <FormSelectInput v-model="formSearch.tag" label="Тег" id="search-tag">
                <option :value="null">Все теги</option>
                <option value="empty">Без тега</option>
                <option
                    v-for="tag in tags"
                    :key="tag"
                    :value="tag.id"
                >
                    {{ tag.name }}
                </option>
            </FormSelectInput>
        </PageGroup>
        <MainBtn v-if="formSearch.date || formSearch.title || formSearch.section || formSearch.tag" @click="reset" class="mb-4">Сбросить фильтр</MainBtn>
        <PageNewsList :news="news.data"/>
        <Pagination :links="news.links" :total="news.total" :length="news.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        sections: Object,
        tags: Object,
        news: Object
    },
    data() {
        return {
            formSearch: {
                archive: this.filters.archive,
                date: this.filters.date,
                title: this.filters.title,
                section: this.filters.section,
                tag: this.filters.tag
            }
        }
    },
    methods: {
        reset() {
            this.formSearch = {
                archive: this.filters.archive,
                date: null,
                title: null,
                section: null,
                tag: null
            };
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/news', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>