<template>
    <Head title="Редактирование адреса"/>
    <PageBlock>
        <PageTitle>Редактирование адреса</PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <RecordInfo :item="address"/>

        <form @submit.prevent="update">
            <PageGroup class="mb-2">
                <FormTextInput v-model="form.post_code" v-model:error="form.errors.post_code" label="Почтовый индекс" placeholder="Введите индекс" type="number" id="post_code" required/>
                <FormTextInput v-model="form.district" v-model:error="form.errors.district" label="Округ" placeholder="Введите округ" id="district"/>
                <FormTextInput v-model="form.region" v-model:error="form.errors.region" label="Регион (область)" placeholder="Введите регион" id="region"/>
                <FormTextInput v-model="form.city" v-model:error="form.errors.city" label="Город" placeholder="Введите город" id="city"/>
                <FormTextInput v-model="form.street" v-model:error="form.errors.street" label="Улица" placeholder="Введите улицу" id="street"/>
                <FormTextInput v-model="form.house_number" v-model:error="form.errors.house_number" label="Номер дома" placeholder="Введите номер дома" id="house_number"/>
            </PageGroup>

            <PageGroupBtn>
                <MainBtn to="/admin/addresses" variation="second">Вернуться назад</MainBtn>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="this.hasAccess('personal_delete')" @click="destroy(address.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn type="submit" :loading="form.processing">Сохранить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </PageBlock>
</template>

<script>
export default {
    props: {
        address: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.address.id,
                post_code: this.address.post_code,
                district: this.address.district,
                region: this.address.region,
                city: this.address.city,
                street: this.address.street,
                house_number: this.address.house_number
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/addresses/${this.form.id}`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        id: this.address.id,
                        post_code: this.address.post_code,
                        district: this.address.district,
                        region: this.address.region,
                        city: this.address.city,
                        street: this.address.street,
                        house_number: this.address.house_number
                    });

                    this.form.reset();
                },
            });
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот адрес?')) {
                this.$inertia.delete(`/admin/addresses/${id}`);
            }
        }
    }
}
</script>