<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <RecordInfoItem
            v-for="val in other"
            :key="val"
            :icon="val.icon"
            :title="val.title"
            :value="val.value"
        />
        <RecordInfoItem v-if="item.created_at" icon="calendar-time" title="Дата создания" :value="new Date(item.created_at).toLocaleString()"/>
        <RecordInfoItem v-if="item.updated_at" icon="calendar-time" title="Последнее обновление" :value="new Date(item.updated_at).toLocaleString()"/>
        <RecordInfoItem v-if="item.deleted_at" icon="calendar-time" title="Дата удаления" :value="new Date(item.deleted_at).toLocaleString()"/>
        <RecordInfoItem v-if="item.owner" icon="user" title="Владелец" :value="`${item.owner.surname ?? ''} ${item.owner.name ?? ''} ${item.owner.patronymic ?? ''}`"/>
        <RecordInfoItem v-if="item.moder" icon="user" title="Последний модератор" :value="`${item.moder.surname ?? ''} ${item.moder.name ?? ''} ${item.moder.patronymic ?? ''}`"/>
        <RecordInfoItem v-if="item.views" icon="eye-check" title="Просмотров" :value="item.views"/>
        <RecordInfoItem v-if="item.rating" icon="stars" title="Рейтинг" :value="item.rating"/>
    </div>
</template>

<script>
export default {
    props: {
        item: Object,
        other: Array
    }
}
</script>