<template>
    <ModalConfirm ref="modalConfirm"/>
    <PageTitle level="2" class="mt-12">
        <Icon name="checks" size="5"/>
        Индивидуальные разрешения пользователей
        <IconBtn @click="showBlock" variation="second" name="caret-down" size="5" title="Показать блок"/>
    </PageTitle>

    <div
        ref="block"
        class="transition-height duration-500 overflow-hidden hidden"
        :class="{ '!block': show }"
    >
        <PageGroup class="mb-2">
            <FormSelectInput @change="selectUser" v-model="form.user" label="Выберите пользователя" id="rights-user">
                <option :value="null">Не выбран</option>
                <option v-for="user in users" :key="user" :value="user">{{ user.surname }} {{ user.name }} {{ user.patronymic }} ({{ user.email }})</option>
            </FormSelectInput>

            <FormSelectInput v-if="form.user" @change="selectType" v-model="form.type" label="Выберите тип" id="rights-type">
                <option :value="null">Не выбрано</option>
                <option value="article">Страница</option>
                <option value="news">Новость</option>
                <option value="event">Мероприятие</option>
            </FormSelectInput>

            <FormSelectInput v-if="form.user && modelList.length" v-model="form.model_id" label="Выберите объект" id="rights-model" required>
                <option :value="null">Не выбрано</option>
                <option
                    v-for="model in modelList"
                    :value="model.id"
                >
                    {{ model.title }} (ID: {{ model.id }})
                </option>
            </FormSelectInput>
        </PageGroup>

        <MainBtn v-if="form.model_id" @click="attach(form.user.id)" type="submit">Добавить</MainBtn>

        <div v-if="form.user" class="grid lg:grid-cols-2 gap-6 mt-6">
            <div>
                <PageTitle level="3">Страницы</PageTitle>
                <PageListBody class="max-h-[400px] overflow-y-auto">
                    <template v-if="form.user.article_rights.length">
                        <PageListItem
                            v-for="article in form.user.article_rights"
                            :key="article"
                        >
                            <div class="flex items-center gap-4">
                                <Icon name="file"/>
                                <a :href="`/admin/articles/${article.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                    (ID: {{ article.id }}) {{ article.title }}
                                </a>
                            </div>
                            <IconBtn @click="detach('article', article.id, form.user.id)" name="x" size="5" variation="danger" title="Удалить"/>
                        </PageListItem>
                    </template>
                    <PageListItem v-else :empty="true">
                        <Icon name="file"/>
                        Разрешения не найдены
                    </PageListItem>
                </PageListBody>
            </div>

            <div>
                <PageTitle level="3">Новости</PageTitle>
                <PageListBody class="max-h-[400px] overflow-y-auto">
                    <template v-if="form.user.news_rights.length">
                        <PageListItem
                            v-for="news in form.user.news_rights"
                            :key="news"
                        >
                            <div class="flex items-center gap-4">
                                <Icon name="news"/>
                                <a :href="`/admin/news/${news.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                    (ID: {{ news.id }}) {{ news.title }}
                                </a>
                            </div>
                            <IconBtn @click="detach('news', news.id, form.user.id)" name="x" size="5" variation="danger" title="Удалить"/>
                        </PageListItem>
                    </template>
                    <PageListItem v-else :empty="true">
                        <Icon name="news"/>
                        Разрешения не найдены
                    </PageListItem>
                </PageListBody>
            </div>

            <div>
                <PageTitle level="3">Мероприятия</PageTitle>
                <PageListBody class="max-h-[400px] overflow-y-auto">
                    <template v-if="form.user.event_rights.length">
                        <PageListItem
                            v-for="event in form.user.event_rights"
                            :key="event"
                        >
                            <div class="flex items-center gap-4">
                                <Icon name="timeline-event-text"/>
                                <a :href="`/admin/events/${event.id}/edit`" target="_blank" class="font-bold hover:text-blue-600 transition">
                                    (ID: {{ event.id }}) {{ event.title }}
                                </a>
                            </div>
                            <IconBtn @click="detach('event', event.id, form.user.id)" name="x" size="5" variation="danger" title="Удалить"/>
                        </PageListItem>
                    </template>
                    <PageListItem v-else :empty="true">
                        <Icon name="calendar-event"/>
                        Разрешения не найдены
                    </PageListItem>
                </PageListBody>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    data() {
        return {
            show: false,
            users: [],
            modelList: [],
            form: {
                user: null,
                type: null,
                model_id: null
            }
        }
    },
    methods: {
        async showBlock() {
            if (this.show) {
                this.show = false;
                this.users = [];
            } else {
                this.show = true;

                this.getUsers();
            }
        },
        async getUsers() {
            await axios.get('/admin/settings/rights/individual/users')
                .then((res) => {
                    this.users = res.data;
                })
                .catch((err) => {
                    notificationStore().add('error', 'При загрузке списка пользователей произошла ошибка', 2500);
                });
        },
        selectUser() {
            this.form.type = null;
            this.form.model_id = null;
            this.modelList = [];

            setTimeout(() => {
                this.$refs.block.style.height = this.$refs.block.scrollHeight;
            }, 250)
        },
        selectType() {
            this.form.model_id = null;

            if(!this.form.type) {
                this.modelList = [];
            } else {
                axios.post('/admin/settings/rights/individual/models', {
                    type: this.form.type,
                    user_id: this.form.user.id
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
        async attach(user_id) {
            if(!this.form.type || !this.form.model_id) {
                notificationStore().add('warning', 'Необходимо выбрать тип и объект', 2500);
            } else if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите добавить это разрешение пользователю?')) {
                axios.post('/admin/settings/rights/individual/attach', {
                    type: this.form.type,
                    model_id: this.form.model_id,
                    user_id: user_id
                })
                    .then(async (res) => {
                        this.form.model_id = null;
                        this.modelList = [];

                        await this.getUsers();
                        this.form.user = this.users.find(u => u.id === user_id) ?? null;
                        this.selectType();

                        notificationStore().add('success', 'Разрешение успешно добавлено', 5000);
                    })
                    .catch((err) => {
                        notificationStore().add('error', 'При добавлении разрешения произошла ошибка', 5000);
                    });
            } else {
                notificationStore().add('info', 'Действие отменено', 2500);
            }
        },
        async detach(type, model_id, user_id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить это разрешение у пользователя?')) {
                axios.post('/admin/settings/rights/individual/detach', {
                    type: type,
                    model_id: model_id,
                    user_id: user_id
                })
                    .then(async (res) => {
                        await this.getUsers();
                        this.form.user = this.users.find(u => u.id === user_id) ?? null;
                        this.form.type = null;
                        this.form.model_id = null;
                        this.modelList = [];

                        notificationStore().add('success', 'Разрешение пользователя успешно удалено', 5000);
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