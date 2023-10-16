<template>
    <Head title="Создание пользователя"/>
    <PageBlock>
        <PageTitle>Создание пользователя</PageTitle>
        <Alert/>

        <PageGroup class="mb-4">
            <FormTextInput v-model="form.surname" v-model:error="form.errors.surname" label="Фамилия" placeholder="Введите фамилию" id="surname" required/>
            <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Имя" placeholder="Введите имя" id="name" required/>
            <FormTextInput v-model="form.patronymic" v-model:error="form.errors.patronymic" label="Отчество" placeholder="Введите отчество" id="patronymic"/>
            <FormTextInput v-model="form.email" v-model:error="form.errors.email" label="Email" placeholder="Введите email" type="email" id="email" required/>
        </PageGroup>

        <PageGroupBtn>
            <MainBtn to="/admin/users" variation="second">Вернуться назад</MainBtn>
            <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
        </PageGroupBtn>
    </PageBlock>
</template>

<script>
export default {
    data() {
        return {
            form: this.$inertia.form({
                surname: null,
                name: null,
                patronymic: null,
                email: null
            })
        }
    },
    methods: {
        store() {
            this.form.post('/admin/users');
        }
    }
}
</script>