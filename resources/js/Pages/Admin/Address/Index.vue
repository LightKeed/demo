<template>
    <Head title="Адреса"/>
    <PageBlock>
        <PageTitle>Адреса</PageTitle>
        <Alert/>
        <template v-if="this.hasAccess('personal_create')">
            <MainBtn v-if="!showForm" @click="toggleForm" class="mb-4">Создать адрес</MainBtn>
            <form
                ref="form"
                @submit.prevent="store"
                class="transition-height duration-500 overflow-hidden"
                :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
            >
                <PageTitle level="2">Создание адреса</PageTitle>
                <PageGroup class="mb-2">
                    <FormTextInput v-model="form.post_code" v-model:error="form.errors.post_code" label="Почтовый индекс" placeholder="Введите индекс" type="number" id="post_code" required/>
                    <FormTextInput v-model="form.district" v-model:error="form.errors.district" label="Округ" placeholder="Введите округ" id="district"/>
                    <FormTextInput v-model="form.region" v-model:error="form.errors.region" label="Регион (область)" placeholder="Введите регион" id="region"/>
                    <FormTextInput v-model="form.city" v-model:error="form.errors.city" label="Город" placeholder="Введите город" id="city"/>
                    <FormTextInput v-model="form.street" v-model:error="form.errors.street" label="Улица" placeholder="Введите улицу" id="street"/>
                    <FormTextInput v-model="form.house_number" v-model:error="form.errors.house_number" label="Номер дома" placeholder="Введите номер дома" id="house_number"/>
                </PageGroup>
                <PageGroupBtn class="mb-4">
                    <MainBtn @click="toggleForm" type="button" variation="second">Отмена</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Создать</MainBtn>
                </PageGroupBtn>
            </form>
        </template>
        <PageGroup class="mb-2">
            <FormTextInput v-model="formSearch.title" label="Поиск" placeholder="Введите адрес" id="search-title"/>
        </PageGroup>
        <PageAddressList :addresses="addresses.data"/>
        <Pagination :links="addresses.links" :total="addresses.total" :length="addresses.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    props: {
        filters: Object,
        addresses: Object
    },
    data() {
        return {
            showForm: false,
            form: this.$inertia.form({
                post_code: null,
                district: null,
                region: null,
                city: null,
                street: null,
                house_number: null
            }),
            formSearch: {
                title: this.filters.title
            }
        }
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        store() {
            this.form.post('/admin/addresses', {
                onSuccess: (res) => {
                    if(res.props.alert.success) {
                        this.showForm = false;
                        this.form.reset();
                    }
                },
            });
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/addresses', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>