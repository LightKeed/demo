<template>
    <ModalConfirm ref="modalConfirm"/>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div
            v-for="category in categories" :key="category"
            class="relative rounded-lg bg-gray-50 border-2 border-gray-300 hover:bg-slate-200 p-4 transition dark:bg-gray-600 dark:hover:bg-gray-900"
            :class="{ '!bg-red-50': category.deleted_at }"
            @dblclick="hasAccess('category_edit') ? this.$inertia.get(`/admin/categories/${category.id}/edit`) : ''"
        >
            <div class="text-base font-bold mb-2 dark:text-white">{{ category.name }}</div>
            <div class="text-gray-600">
                <p class="flex items-center gap-1 mb-1 dark:text-white"><Icon name="category-2" size="5"/> Подразделов:<span>{{ category.childsCount }}</span></p>
                <p class="flex items-center gap-1 mb-1 dark:text-white"><Icon name="file" size="5"/> Страниц:<span>{{ category.articlesCount }}</span></p>
                <p class="w-fit relative group flex items-center justify-center gap-1 dark:text-white">
                    <Icon name="calendar-time" size="5"/>
                    {{ new Date(category.created_at).toLocaleString() }}
                    <Tooltip title="Дата создания"/>
                </p>
            </div>
            <div class="absolute top-4 right-4 flex items-center gap-1.5">
                <IconBtn v-if="hasAccess('category_restore') && category.deleted_at" @click="restore(category.id)" name="rotate-clockwise" size="5" variation="other" title="Восстановить"/>
                <template v-if="hasAccess('category_edit')">
                    <IconBtn v-if="!category.deleted_at" :to="`/admin/categories/${category.id}/edit`" name="edit" size="5" variation="second" title="Редактировать"/>
                    <IconBtn v-else :to="`/admin/categories/${category.id}/edit`" name="eye" size="5" variation="second" title="Просмотреть"/>
                </template>
                <IconBtn v-if="hasAccess('category_delete')" @click="destroy(category.id)" :name="category.deleted_at ? 'trash-x' : 'trash'" size="5" variation="danger" title="Удалить"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        categories: Object
    },
    methods: {
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот раздел?')) {
                this.$inertia.post(`/admin/categories/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот раздел?')) {
                this.$inertia.delete(`/admin/categories/${id}`);
            }
        }
    }
}
</script>