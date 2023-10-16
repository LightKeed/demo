<template>
    <Head title="Редактирование права"/>
    <PageBlock>
        <PageTitle>Редактирование права</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="permission"/>

        <form @submit.prevent="update" class="mb-6">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название" placeholder="Введите название права" id="name" required/>
                <FormTextInput v-model="form.display_name" v-model:error="form.errors.display_name" label="Отображаемое имя" placeholder="Введите отображаемое имя" id="displayname" required/>
                <FormTextInput v-model="form.group" v-model:error="form.errors.group" label="Группа" placeholder="Введите название группы" id="group"/>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn to="/admin/settings/rights" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn @click="destroy(permission.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>

        <PageRightsUserPermissionList :permission="permission.name" :users="users"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        permission: Object,
        users: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.permission.id,
                name: this.permission.name,
                display_name: this.permission.display_name,
                group: this.permission.group,
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/settings/rights/permission/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.permission.id,
                        name: this.permission.name,
                        display_name: this.permission.display_name,
                        group: this.permission.group,
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это право?')) {
                this.$inertia.delete(`/admin/settings/rights/permission/${id}`);
            }
        }
    }
}
</script>