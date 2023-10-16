<template>
    <Head title="Создание подразделения"/>
    <PageBlock>
        <PageTitle>Создание подразделения</PageTitle>
        <Alert/>

        <PageGroup class="mb-4">
            <FormSelectInput v-model="form.type_id" v-model:error="form.errors.type_id" label="Тип подразделения" id="type_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="type in types"
                    :key="type"
                    :value="type.id"
                >
                    {{ type.title }}
                </option>
            </FormSelectInput>
            <FormSelectInput v-model="form.address_id" v-model:error="form.errors.address_id" label="Адрес" id="address_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="address in addresses"
                    :key="address"
                    :value="address.id"
                >
                    {{ getAddress(address) }}
                </option>
            </FormSelectInput>
            <FormTextInput v-model="form.cabinet" v-model:error="form.errors.cabinet" label="Номер кабинета" placeholder="Введите номер кабинета" id="cabinet"/>
            <FormSelectInput v-model="form.department_id" v-model:error="form.errors.department_id" label="Родительское подразделение" id="department_id">
                <option :value="null">Не выбрано</option>
                <option
                    v-for="department in departments"
                    :key="department"
                    :value="department.id"
                >
                    {{ department.title }}
                </option>
            </FormSelectInput>
            <FormTextInput v-model="form.title" v-model:error="form.errors.title" label="Название" placeholder="Введите название" id="title" required/>
            <FormTextInput v-model="form.title_short" v-model:error="form.errors.title_short" label="Короткое название" placeholder="Введите короткое название" id="title_short"/>
            <FormTextInput v-model="form.email" v-model:error="form.errors.email" label="Email" placeholder="Введите email" type="email" id="email"/>
            <FormTextInput v-model="form.phone" v-model:error="form.errors.phone" label="Номер телефона" placeholder="Введите номер телефона" id="phone"/>
        </PageGroup>

        <FormMediaInput v-model="form.photo_id" v-model:error="form.errors.photo_id" label="Фотография" type="image" mode="single" preview="true"/>

        <PageGroupBtn>
            <MainBtn to="/admin/departments" variation="second">Вернуться назад</MainBtn>
            <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
        </PageGroupBtn>
    </PageBlock>
</template>

<script>
export default {
    props: {
        types: Object,
        addresses: Object,
        departments: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                title: null,
                title_short: null,
                email: null,
                phone: null,
                cabinet: null,
                department_id: null,
                photo_id: null,
                address_id: null,
                type_id: null
            })
        }
    },
    methods: {
        store() {
            this.form.post('/admin/departments');
        }
    }
}
</script>