<template>
    <Head :title="`${user.deleted_at ? 'Просмотр удаленного ' : 'Редактирование'} пользователя`"/>
    <PageBlock>
        <PageTitle>{{ user.deleted_at ? 'Просмотр удаленного ' : 'Редактирование' }} пользователя</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="user"/>

        <form @submit.prevent="update" class="mb-6">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.surname" v-model:error="form.errors.surname" label="Фамилия" placeholder="Введите фамилию" id="surname" required :disabled="user.deleted_at"/>
                <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Имя" placeholder="Введите имя" id="name" required :disabled="user.deleted_at"/>
                <FormTextInput v-model="form.patronymic" v-model:error="form.errors.patronymic" label="Отчество" placeholder="Введите отчество" id="patronymic" :disabled="user.deleted_at"/>
                <FormTextInput v-model="form.email" v-model:error="form.errors.email" label="Email" placeholder="Введите email" type="email" id="email" required :disabled="user.deleted_at"/>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn :to="user.deleted_at ? '/admin/users?archive=1' : '/admin/users'" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn @click="destroy(user.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="!user.deleted_at" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else @click="restore(user.id)" variation="other" type="button">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>

        <div class="lg:grid lg:grid-cols-2 gap-6 mb-12">
            <PageUserRoleList :user="user" :roles="roles"/>
            <PageUserPermissionList :user="user" :permissions="permissions" :groups="groups"/>
        </div>

        <PageUserIndividualRightsList :user="user"/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        user: Object,
        roles: Object,
        permissions: Object,
        groups: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.user.id,
                surname: this.user.surname,
                name: this.user.name,
                patronymic: this.user.patronymic,
                email: this.user.email,
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/users/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.user.id,
                        surname: this.user.surname,
                        name: this.user.name,
                        patronymic: this.user.patronymic,
                        email: this.user.email,
                    });

                    this.form.reset();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этого пользователя?')) {
                this.$inertia.post(`/admin/users/${id}/restore`);
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этого пользователя?')) {
                this.$inertia.delete(`/admin/users/${id}`);
            }
        }
    }
}
</script>