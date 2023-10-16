<template>
    <Head title="Редактирование типа подразделения"/>
    <PageBlock>
        <PageTitle>Редактирование типа подразделения</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="type"/>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required/>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn to="/admin/department-types" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="this.hasAccess('personal_delete')" @click="destroy(type.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </PageBlock>
</template>

<script>
export default {
    props: {
        type: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.type.id,
                title: this.type.title
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/department-types/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.type.id,
                        title: this.type.title
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот тип подразделения?')) {
                this.$inertia.delete(`/admin/department-types/${id}`);
            }
        }
    }
}
</script>