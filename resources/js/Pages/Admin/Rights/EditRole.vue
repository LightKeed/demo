<template>
    <Head title="Редактирование роли"/>
    <PageBlock>
        <PageTitle>Редактирование роли</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="role"/>

        <form @submit.prevent="update" class="mb-6">
            <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название" placeholder="Введите название роли" id="name"/>

            <PageGroupBtn>
                <MainBtn to="/admin/settings/rights" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn @click="destroy(role.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>

        <PageRightsUserRoleList :role="role.name" :users="users"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        role: Object,
        users: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.role.id,
                name: this.role.name,
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/settings/rights/role/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.role.id,
                        name: this.role.name,
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить эту роль?')) {
                this.$inertia.delete(`/admin/settings/rights/role/${id}`);
            }
        }
    }
}
</script>