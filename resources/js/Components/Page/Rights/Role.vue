<template>
    <PageTitle level="2">
        <Icon name="user-check" size="5"/>
        Роли
    </PageTitle>
    <MainBtn @click="showForm = !showForm" class="mb-4">Создать роль</MainBtn>
    <form
        ref="form"
        @submit.prevent="store"
        class="transition-height duration-500 overflow-hidden"
        :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
    >
        <PageTitle level="2">Создание роли</PageTitle>

        <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Название" placeholder="Введите название роли" id="name" required/>

        <PageGroupBtn class="mb-4">
            <MainBtn @click="showForm = false" type="button" variation="second">Отмена</MainBtn>
            <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
        </PageGroupBtn>
    </form>
    <PageRightsRoleList :roles="roles"/>
</template>

<script>
export default {
    props: {
        roles: Object,
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                name: null
            })
        }
    },
    methods: {
        store() {
            this.form.post('/admin/settings/rights/role', {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        },
    }
}
</script>