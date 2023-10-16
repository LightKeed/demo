<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="events.length">
            <PageListItem
                v-for="event in events" :key="event"
                :deleted="event.deleted_at"
                @dblclick="hasAccessModel('event', event.id) ? this.$inertia.get(`/admin/events/${event.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="timeline-event-text"/>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="font-bold">{{ event.title }}</span>
                            <span class="text-xs">({{ event.slug }})</span>
                        </div>
                        <div v-if="event.tags.length" class="flex flex-wrap items-center gap-2 mb-2">
                            <span
                                v-for="tag in event.tags"
                                :key="tag"
                                class="text-xs bg-indigo-600 text-white rounded-full px-2 py-0.5"
                            >
                                {{ tag.name }}
                            </span>
                        </div>
                        <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="calendar-time" size="5"/>
                                {{ new Date(event.created_at).toLocaleString() }}
                                <Tooltip title="Дата создания"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="eye-check" size="5"/>
                                {{ event.views }}
                                <Tooltip title="Просмотров"/>
                            </div>
                            <div class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="clock-play" size="5"/>
                                {{ new Date(event.event_start_at).toLocaleString([], {dateStyle: 'short'}) }}
                                <Tooltip title="Начало мероприятия"/>
                            </div>
                            <div v-if="event.event_end_at" class="w-fit relative group flex items-center justify-center gap-1">
                                <Icon name="clock-stop" size="5"/>
                                {{ new Date(event.event_end_at).toLocaleString([], {dateStyle: 'short'}) }}
                                <Tooltip title="Завершение мероприятия"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <template v-if="hasAccess('event_hiding') && !event.deleted_at">
                        <IconBtn v-if="event.enabled" @click="toggleVisible(event.id, 'hide')" name="eye" size="5" title="Скрыть"/>
                        <IconBtn v-else @click="toggleVisible(event.id, 'show')" name="eye-off" size="5" variation="second" title="Показать"/>
                    </template>
                    <IconBtn v-if="hasAccess('article_restore') && event.deleted_at" @click="restore(event.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                    <template v-if="hasAccessModel('event', event.id)">
                        <IconBtn v-if="!event.deleted_at" :to="`/admin/events/${event.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                        <IconBtn v-else :to="`/admin/events/${event.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                    </template>
                    <IconBtn v-if="hasAccess('event_delete')" @click="destroy(event.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="calendar-event"/>
            Мероприятия не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        events: Object
    },
    methods: {
        async toggleVisible(id, action) {
            if(await this.$refs.modalConfirm.show(null, `Вы действительно хотите ${action === 'hide' ? 'скрыть' : 'показать'} это мероприятие?`)) {
                this.$inertia.post(`/admin/events/${id}/visible`);
            }
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить это мероприятие?')) {
                this.$inertia.post(`/admin/events/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это мероприятие?')) {
                this.$inertia.delete(`/admin/events/${id}`);
            }
        }
    }
}
</script>