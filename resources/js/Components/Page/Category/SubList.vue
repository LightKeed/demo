<template>
    <PageTitle class="mt-4">Подразделы</PageTitle>
    <MainBtn v-if="hasAccess('category_create') && !category.deleted_at" class="mb-4" @click="toggleForm(category)">Создать подраздел</MainBtn>
    <ModalConfirm ref="modalConfirm"/>
    <PageCategoryModalForm ref="modalForm"/>
    <PageListBody>
        <template v-if="category.children_categories.length">
            <PageListItem
                v-for="child_l1 in category.children_categories"
                :key="child_l1"
                class="!block !p-0 hover:!bg-inherit"
            >
                <div
                    class="flex items-center justify-between gap-4 p-4 hover:bg-slate-200 transition dark:hover:bg-slate-500"
                    :class="{ '!bg-red-50 hover:!bg-red-100 hover:!text-gray-900': child_l1.deleted_at }"
                    @dblclick="hasAccess('category_edit') ? toggleForm(category, child_l1) : ''"
                >
                    <div class="flex items-center gap-4">
                        <Icon name="category-2"/>
                        <div class="flex items-center gap-1">
                            {{ child_l1.name }}
                            <template v-if="hasAccess('category_hiding') && !child_l1.deleted_at">
                                <IconBtn v-if="child_l1.enabled" @click="toggleVisible(child_l1.id, 'hide')" name="eye" size="4" title="Скрыть" class="ml-1"/>
                                <IconBtn v-else @click="toggleVisible(child_l1.id, 'show')" name="eye-off" size="4" variation="second" title="Показать" class="ml-1"/>
                            </template>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <IconBtn v-if="hasAccess('category_restore') && child_l1.deleted_at" @click="restore(child_l1.id)" name="reload" size="5" variation="other" title="Восстановить"/>
                        <IconBtn v-if="hasAccess('category_create') && !child_l1.deleted_at" @click="toggleForm(child_l1)" name="plus" size="5" variation="success" title="Добавить подраздел"/>
                        <template v-if="hasAccess('category_edit')">
                            <IconBtn v-if="!child_l1.deleted_at" @click="toggleForm(category, child_l1)" name="edit" size="5" variation="second" title="Редактировать"/>
                            <IconBtn v-else @click="toggleForm(category, child_l1)" name="eye" size="5" variation="second" title="Просмотреть"/>
                        </template>
                        <IconBtn v-if="hasAccess('category_delete')" @click="destroy(child_l1.id)" :name="child_l1.deleted_at ? 'trash-x' : 'trash'" size="5" variation="danger" title="Удалить"/>
                    </div>
                </div>
                <PageListItem
                    v-if="child_l1.children_categories.length"
                    v-for="child_l2 in child_l1.children_categories"
                    :key="child_l2"
                    class="!py-2 !pl-6 !pr-4"
                    :deleted="child_l2.deleted_at"
                    @dblclick="hasAccess('category_edit') ? toggleForm(child_l1, child_l2) : ''"
                >
                    <div class="flex items-center gap-4">
                        <Icon name="grip-horizontal" class="min-w-[24px]"/>
                        <div class="flex items-center gap-1">
                            {{ child_l2.name }}
                            <template v-if="hasAccess('category_hiding') && !child_l2.deleted_at">
                                <IconBtn v-if="child_l2.enabled" @click="toggleVisible(child_l2.id, 'hide')" name="eye" size="4" title="Скрыть" class="ml-1"/>
                                <IconBtn v-else @click="toggleVisible(child_l2.id, 'show')" name="eye-off" size="4" variation="second" title="Показать" class="ml-1"/>
                            </template>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <IconBtn v-if="hasAccess('category_restore') && child_l2.deleted_at" @click="restore(child_l2.id)" name="reload" size="5" variation="other" title="Восстановить"/>
                        <template v-if="hasAccess('category_edit')">
                            <IconBtn v-if="!child_l2.deleted_at" @click="toggleForm(child_l1, child_l2)" name="edit" size="5" variation="second" title="Редактировать"/>
                            <IconBtn v-else @click="toggleForm(child_l1, child_l2)" name="eye" size="5" variation="second" title="Просмотреть"/>
                        </template>
                        <IconBtn v-if="hasAccess('category_delete')" @click="destroy(child_l2.id)" :name="child_l2.deleted_at ? 'trash-x' : 'trash'" size="5" variation="danger" title="Удалить"/>
                    </div>
                </PageListItem>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="category-2"/>
            Подразделы не найдены
        </PageListItem>
    </PageListBody>
</template>

<script>
export default {
    props: {
        category: Object
    },
    methods: {
        toggleForm(parent, category) {
            this.$refs.modalForm.show(parent, category)
        },
        update() {
            this.form.put(`/admin/categories/${this.form.id}`, {
                onSuccess: (res) => this.form.reset(),
            });
        },
        async toggleVisible(id, action) {
            if(await this.$refs.modalConfirm.show(null, `Вы действительно хотите ${action === 'hide' ? 'скрыть' : 'показать'} этот раздел?`)) {
                this.$inertia.post(`/admin/categories/${id}/visible`);
            }
        },
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