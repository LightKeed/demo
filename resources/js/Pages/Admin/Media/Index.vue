<template>
    <Head :title="filters.archive ? 'Медиафайлы - Архив' : 'Медиафайлы'"/>
    <PageBlock>
        <PageTitle class="flex flex-wrap items-center justify-between gap-2">
            {{ filters.archive ? 'Медиафайлы - Архив' : 'Медиафайлы' }}
            <div class="flex gap-2">
                <IconBtn v-if="this.hasAccess('media_restore') || this.hasAccess('media_delete')" @click="switchMultipleSelect" name="box-multiple" size="5" title="Множественный выбор"/>
                <template v-if="this.hasAccess('media_restore')">
                    <IconBtn v-if="!filters.archive" :to="`/admin/media?archive=1`" name="archive" size="5" variation="other" title="Архив"/>
                    <IconBtn v-else :to="`/admin/media`" name="database" size="5" title="Основные данные"/>
                </template>
            </div>
        </PageTitle>
        <Alert/>
        <ModalConfirm ref="modalConfirm"/>

        <MainBtn v-if="this.hasAccess('media_create')" @click="showUpload" class="mb-4" :disabled="multipleSelect">Загрузить файл</MainBtn>

        <PageMediaModalUpload ref="modalUpload"/>

        <transition name="fade" mode="out-in">
            <div v-if="!multipleSelect">
                <FormSwitchInput v-model="formSearch.my" label="Мои файлы" id="search-my" class="mb-4"/>
                <PageGroup class="mb-4">
                    <FormSelectInput v-model="formSearch.type" label="Тип" id="search-type">
                        <option :value="null">Все медиафайлы</option>
                        <option value="empty">Неопределенный тип</option>
                        <option value="image">Изображения</option>
                        <option value="audio">Аудио</option>
                        <option value="video">Видео</option>
                        <option value="document">Документы</option>
                        <option value="table">Электронные таблицы</option>
                        <option value="archive">Архивы</option>
                    </FormSelectInput>
                    <FormTextInput v-model="formSearch.created" label="Дата загрузки" type="date" id="search-created"/>
                    <FormTextInput v-model="formSearch.title" label="Поиск" placeholder="Введите название" id="search-title"/>
                </PageGroup>
            </div>
            <div v-else>
                <PageTitle level="2">Действия над несколькими файлами</PageTitle>
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <MainBtn v-if="this.hasAccess('media_restore') && filters.archive" @click="restoreMultiple" variation="other">Восстановить</MainBtn>
                    <MainBtn v-if="this.hasAccess('media_delete')" @click="destroyMultiple" variation="danger">Удалить</MainBtn>
                    <MainBtn @click="switchMultipleSelect" variation="second">Отмена</MainBtn>
                </div>
            </div>
        </transition>

        <PageMediaGrid :files="media.data" :multipleSelect="multipleSelect" v-model:selectedFiles="selectedFiles"/>

        <Pagination :links="media.links" :total="media.total" :length="media.data.length ?? null"/>
    </PageBlock>
</template>

<script>
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        filters: Object,
        media: Object
    },
    data() {
        return {
            multipleSelect: false,
            selectedFiles: [],
            formSearch: {
                archive: this.filters.archive,
                title: this.filters.title,
                type: this.filters.type,
                created: this.filters.created,
                my: this.filters.my
            }
        }
    },
    methods: {
        showUpload() {
            this.$refs.modalUpload.show();
        },
        switchMultipleSelect() {
            this.multipleSelect = !this.multipleSelect;

            if(!this.multipleSelect) {
                this.selectedFiles = [];
            }
        },
        async restoreMultiple(id) {
            if(!this.selectedFiles.length) {
                notificationStore().add('warning', 'Необходимо выбрать файлы!', 2500);
            } else {
                if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить выбранные файлы?')) {
                    this.$inertia.post(`/admin/media/restore`, { items: this.selectedFiles }, {
                        onSuccess: () => {
                            this.switchMultipleSelect();
                        }
                    });
                }
            }
        },
        async destroyMultiple(id) {
            if(!this.selectedFiles.length) {
                notificationStore().add('warning', 'Необходимо выбрать файлы!', 2500);
            } else {
                if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить выбранные файлы?')) {
                    this.$inertia.post(`/admin/media/destroy`, { items: this.selectedFiles }, {
                        onSuccess: () => {
                            this.switchMultipleSelect();
                        }
                    });
                }
            }
        }
    },
    watch: {
        formSearch: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/admin/media', pickBy(this.formSearch), { preserveState: true })
            }, 150),
        },
    }
}
</script>