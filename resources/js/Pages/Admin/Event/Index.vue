<template>
    <Head :title="filters.archive ? 'Мероприятия - Архив' : 'Мероприятия'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Мероприятия - Архив' : 'Мероприятия' }}
            <template v-if="hasAccess('event_restore')">
                <IconBtn v-if="!filters.archive" :to="`/admin/events?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                <IconBtn v-else :to="`/admin/events`" name="database" size="5" title="Основные данные"/>
            </template>
        </PageTitle>
        <Alert/>
        <MainBtn v-if="hasAccess('event_create') && !filters.archive" to="/admin/events/create" class="mb-4">Создать мероприятие</MainBtn>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Название" placeholder="Введите название" id="search-title"/>
            <FormSelectInput v-model="formSearch.tag" label="Тег" id="search-category">
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
        <PageEventList :events="events.data"/>
        <Pagination :links="events.links" :total="events.total" :length="events.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        tags: Object,
        events: Object
    },
    data() {
        return {
            formSearch: {
                archive: this.filters.archive,
                title: this.filters.title,
                tag: this.filters.tag
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/events', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>