<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow v-model:show="visible" @cancel="cancel">
        <PageTitle>Карточки сотрудников</PageTitle>

        <IconBtn @click="cancel" name="x" title="Отмена" variation="empty" class="!absolute !top-4 !right-4"/>

        <PageGroup class="mb-4">
            <FormTextInput v-model="formSearch.fio" label="ФИО" placeholder="Введите ФИО" id="search-fio"/>
            <FormTextInput v-model="formSearch.department" label="Подразделение" placeholder="Введите подразделение" id="search-department"/>
        </PageGroup>

        <PersonalCardGrid :cards="cards" :selectedItems="selectedItems" @select="select"/>

        <ModalPagination v-model:page="formSearch.page" :links="cards.links" :total="cards.total" :length="cards.data ? cards.data.length : null"/>

        <PageGroupBtn>
            <MainBtn @click="cancel" variation="second">Отмена</MainBtn>
            <MainBtn @click="submit">Прикрепить</MainBtn>
        </PageGroupBtn>
    </ModalWindow>
</template>

<script>
import axios from 'axios';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';

export default {
    data() {
        return {
            visible: false,
            formSearch: {
                page: 1,
                type: this.type,
                fio: null,
                department: null
            },
            type: 1,
            cards: [],
            selectedItems: [],
            resolvePromise: null,
            rejectPromise: null
        }
    },
    methods: {
        getCards() {
            axios.get('/admin/employees/positions', { params: pickBy(this.formSearch) })
                .then(res => {
                    this.selectedItems = [];
                    this.cards = res.data;
                });
        },
        select(card) {
            let index = this.selectedItems.findIndex(item => item.id === card.id);

            if(index >= 0) {
                this.selectedItems.splice(index, 1);
            } else {
                this.selectedItems.push(card);
            }
        },
        show(type) {
            this.selectedItems = [];
            this.visible = true;
            this.type = type;
            this.page = 1;
            this.getCards();

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        submit() {
            this.visible = false;

            this.resolvePromise(this.selectedItems);
        },
        cancel() {
            this.visible = false;
            this.resolvePromise(null);
        },
    },
    watch: {
        formSearch: {
            handler: throttle(function () {
                this.getCards();
            }, 150),
            deep: true
        },
    }
}
</script>