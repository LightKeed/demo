<template>
    <Head :title="filters.archive ? 'Комментарии - Архив' : 'Комментарии'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Комментарии - Архив' : 'Комментарии' }}
            <template v-if="hasAccess('comment_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/comments?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/comments`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>

        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.scope" label="Область действия" id="search-scope">
                <option :value="null">Все области</option>
                <option value="empty">Не указана</option>
                <option value="news">Новости</option>
                <option value="events">Мероприятия</option>
            </FormSelectInput>
        </PageGroup>
        <PageCommentList :comments="comments.data"/>
        <Pagination :links="comments.links" :total="comments.total" :length="comments.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        comments: Object
    },
    data() {
        return {
            formSearch: {
                status: this.filters.status,
                date: this.filters.date,
                name: this.filters.name,
                email: this.filters.email,
                phone: this.filters.phone,
                archive: this.filters.archive
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/comments', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>