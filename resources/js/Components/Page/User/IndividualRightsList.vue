<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageTitle>
        Индивидуальные разрешения
        <IconBtn @click="showForm = !showForm" name="plus" size="5" variation="success" title="Добавить"/>
    </PageTitle>

    <form
        ref="form"
        @submit.prevent="attach"
        class="transition-height duration-500 overflow-hidden"
        :style="{ height: showForm ? $refs.form.scrollHeight + 'px' : '0px' }"
    >
        <PageTitle level="2">Добавление разрешения</PageTitle>

        <div class="grid lg:grid-cols-2 gap-6 mb-2">
            <FormSelectInput @change="selectType()" v-model="form.type" label="Выберите тип" id="rights-type" required>
                <option :value="null">Не выбрано</option>
                <option value="article">Страница</option>
                <option value="news">Новость</option>
                <option value="event">Мероприятие</option>
            </FormSelectInput>
            <FormSelectInput v-if="modelList.length" v-model="form.model_id" label="Выберите объект" id="rights-model" required>
                <option :value="null">Не выбрано</option>
                <option
                    v-for="model in modelList"
                    :value="model.id"
                >
                    {{ model.title }} (ID: {{ model.id }})
                </option>
            </FormSelectInput>
        </div>
        <PageGroupBtn class="mb-4">
            <MainBtn @click="showForm = false" type="button" variation="second">Отмена</MainBtn>
            <MainBtn type="submit">Добавить</MainBtn>
        </PageGroupBtn>
    </form>

    <div class="lg:grid lg:grid-cols-2 gap-6">
        <div>
            <PageTitle level="2">Страницы</PageTitle>
            <PageListBody class="max-h-[400px] overflow-y-auto">
                <template v-if="user.article_rights.length">
                    <PageListItem
                        v-for="article in user.article_rights"
                        :key="article"
                    >
                        <div class="flex items-center gap-4">
                            <Icon name="file"/>
                            <a :href="`/admin/articles/${article.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                (ID: {{ article.id }}) {{ article.title }}
                            </a>
                        </div>
                        <IconBtn @click="detach('article', article.id)" name="x" size="5" variation="danger" title="Удалить"/>
                    </PageListItem>
                </template>
                <PageListItem v-else :empty="true">
                    <Icon name="file"/>
                    Разрешения не найдены
                </PageListItem>
            </PageListBody>
        </div>

        <div>
            <PageTitle level="2">Новости</PageTitle>
            <PageListBody class="max-h-[400px] overflow-y-auto">
                <template v-if="user.news_rights.length">
                    <PageListItem
                        v-for="news in user.news_rights"
                        :key="news"
                    >
                        <div class="flex items-center gap-4">
                            <Icon name="news"/>
                            <a :href="`/admin/news/${news.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                (ID: {{ news.id }}) {{ news.title }}
                            </a>
                        </div>
                        <IconBtn @click="detach('news', news.id)" name="x" size="5" variation="danger" title="Удалить"/>
                    </PageListItem>
                </template>
                <PageListItem v-else :empty="true">
                    <Icon name="news"/>
                    Разрешения не найдены
                </PageListItem>
            </PageListBody>
        </div>

        <div>
            <PageTitle level="2">Мероприятия</PageTitle>
            <PageListBody class="max-h-[400px] overflow-y-auto">
                <template v-if="user.event_rights.length">
                    <PageListItem
                        v-for="event in user.event_rights"
                        :key="event"
                    >
                        <div class="flex items-center gap-4">
                            <Icon name="timeline-event-text"/>
                            <a :href="`/admin/events/${event.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                (ID: {{ event.id }}) {{ event.title }}
                            </a>
                        </div>
                        <IconBtn @click="detach('event', event.id)" name="x" size="5" variation="danger" title="Удалить"/>
                    </PageListItem>
                </template>
                <PageListItem v-else :empty="true">
                    <Icon name="calendar-event"/>
                    Разрешения не найдены
                </PageListItem>
            </PageListBody>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { router } from '@inertiajs/vue3'
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        user: Object
    },
    data() {
        return {
            showForm: false,
            modelList: [],
            form: {
                type: null,
                model_id: null
            }
        }
    },
    methods: {
        selectType() {
            this.form.model_id = null;

            if(!this.form.type) {
                this.modelList = [];
            } else {
                axios.post('/admin/settings/rights/individual/models', {
                    type: this.form.type,
                    user_id: this.user.id
                })
                    .then((res) => {
                        this.modelList = res.data;
                        notificationStore().add('info', 'Список объектов успешно загружен', 2500);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При загрузки списка объектов произошла ошибка', 2500);
                    });
            }
        },
        async attach() {
            if(!this.form.type || !this.form.model_id) {
                notificationStore().add('warning', 'Необходимо выбрать тип и объект', 2500);
            } else if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите добавить это разрешение пользователю?')) {
                axios.post('/admin/settings/rights/individual/attach', {
                    type: this.form.type,
                    model_id: this.form.model_id,
                    user_id: this.user.id
                })
                    .then((res) => {
                        this.showForm = false;
                        this.form.type = null;
                        this.form.model_id = null;
                        this.modelList = [];

                        notificationStore().add('success', 'Разрешение успешно добавлено', 5000);
                        router.reload({ only: ['user'] });
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При добавлении разрешения произошла ошибка', 5000);
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
            }
        },
        async detach(type, model_id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это разрешение у пользователя?')) {
                axios.post('/admin/settings/rights/individual/detach', {
                    type: type,
                    model_id: model_id,
                    user_id: this.user.id
                })
                    .then((res) => {
                        this.form.type = null;
                        this.form.model_id = null;
                        this.modelList = [];

                        notificationStore().add('success', 'Разрешение пользователя успешно удалено', 5000);
                        router.reload({ only: ['user'] });
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При удалении разрешения пользователя произошла ошибка', 5000);
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
            }
        }
    }
}
</script>