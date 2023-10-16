<template>
    <Head :title="filters.archive ? 'Страницы - Архив' : 'Страницы'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Страницы - Архив' : 'Страницы' }}
            <template v-if="hasAccess('article_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/articles?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/articles`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <MainBtn v-if="hasAccess('article_create') && !filters.archive" to="/admin/articles/create" class="mb-4">Создать страницу</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.category" label="Раздел" id="search-category">
                <option :value="null">Все разделы</option>
                <option value="empty">Без категории</option>
                <template v-for="category in categories" :key="category">
                    <option :value="category.id">{{ category.name }}</option>
                    <template v-for="child_l1 in category.children_categories" :key="child_l1">
                        <option :value="child_l1.id">- {{ child_l1.name }}</option>
                        <option v-for="child_l2 in child_l1.children_categories" :key="child_l2" :value="child_l2.id">-- {{ child_l2.name }}</option>
                    </template>
                </template>
            </FormSelectInput>
        </PageGroup>
        <PageArticleList :articles="articles.data"/>
        <Pagination :links="articles.links" :total="articles.total" :length="articles.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        categories: Object,
        articles: Object
    },
    data() {
        return {
            formSearch: {
                title: this.filters.title,
                category: this.filters.category,
                archive: this.filters.archive
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/articles', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>