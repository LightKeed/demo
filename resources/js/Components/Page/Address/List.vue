<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageListBody>
        <template v-if="addresses.length">
            <PageListItem
                v-for="address in addresses"
                :key="address"
                @dblclick="this.hasAccess('personal_edit') ? this.$inertia.get(`/admin/addresses/${address.id}/edit`) : ''"
            >
                <div class="flex items-center gap-4">
                    <Icon name="map-pin"/>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold mb-1">
                            {{ getAddress(address) }}
                        </span>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5"/>
                            {{ new Date(address.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <IconBtn v-if="this.hasAccess('personal_edit')" :to="`/admin/addresses/${address.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-if="this.hasAccess('personal_delete')" @click="destroy(address.id)" name="trash" size="5" variation="danger" title="Удалить"/>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="map-pins"/>
            Адреса не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        addresses: Object
    },
    methods: {
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот адрес?')) {
                this.$inertia.delete(`/admin/addresses/${id}`);
            }
        }
    }
}
</script>