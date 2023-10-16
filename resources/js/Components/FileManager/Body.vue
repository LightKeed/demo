<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow v-model:show="visible" @cancel="cancel">
        <PageTitle>Файловой менеджер</PageTitle>

        <div v-if="loading" class="absolute top-0 left-0 flex items-center justify-center w-full h-full z-[100] bg-slate-200/80">
            <svg class="animate-spin -ml-1 mr-2 h-[50px] w-[50px] text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <IconBtn @click="cancel" name="x" title="Отмена" variation="empty" class="!absolute !top-4 !right-4"/>
        <MainBtn v-if="this.hasAccess('media_create')" @click="$refs.inputFile.showPicker()" class="mb-4">Загрузить и прикрепить файлы</MainBtn>

        <input @change="selectFiles" ref="inputFile" type="file" class="hidden" id="upload-files" :multiple="selectionMode !== 'single'">

        <div class="mb-4">
            <FormSwitchInput v-model="formSearch.my" label="Мои файлы" id="search-my" class="mb-4"/>
            <PageGroup class="mb-4">
                <FormSelectInput v-if="!type && !formSearch.type" v-model="formSearch.type" label="Тип" id="search-type">
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

        <FileManagerGrid :media="media" :selectedFiles="selectedFiles" @select="select"/>

        <ModalPagination v-model:page="formSearch.page" :links="media.links" :total="media.total" :length="media.data ? media.data.length : null"/>

        <PageGroupBtn>
            <MainBtn @click="cancel" variation="second" type="button" >Отмена</MainBtn>
            <MainBtn @click="submit" type="button" >Прикрепить</MainBtn>
        </PageGroupBtn>
    </ModalWindow>
</template>

<script>
import axios from 'axios';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        type: String
    },
    data() {
        return {
            visible: false,
            loading: false,
            formSearch: {
                page: 1,
                type: this.type ?? null,
                created: null,
                title: null,
                scope: null
            },
            media: [],
            multipleSelect: false,
            selectionMode: 'multi',
            selectedFiles: [],
            resolvePromise: null,
            rejectPromise: null
        }
    },
    methods: {
        getMedia() {
            axios.get('/admin/media/manager', { params: pickBy(this.formSearch) })
                .then(res => {
                    this.selectedFiles = [];
                    this.media = res.data;
                });
        },
        async selectFiles(e) {
            this.loading = true;
            this.selectedFiles = [];

            const files = Array.from(e.target.files);
            this.$refs.inputFile.value = null;

            for (const file of files) {
                const uploadFile = await axios.post('/admin/media/upload', { files: [file], type: this.formSearch.type }, { headers: {'Content-Type': 'multipart/form-data'} })
                    .then(res => {
                        notificationStore().add('success', 'Файл успешно загружен и прикреплен', 2500);
                        return res.data;
                    })
                    .catch(rest => {
                        notificationStore().add('error', 'При загрузке файла произошла ошибка', 2500);
                        console.log("ERROR");
                    });

                if(uploadFile) {
                    this.selectedFiles.push(uploadFile);
                }
            }

            if(this.selectedFiles.length) {
                this.submit();
            } else {
                notificationStore().add('info', 'Действие отменено', 1500);
            }

            this.loading = false;
        },
        select(file) {
            let index = this.selectedFiles.findIndex(f => f.id === file.id);

            if(index >= 0) {
                this.selectionMode === 'single' ? this.selectedFiles = [] : this.selectedFiles.splice(index, 1);
            } else {
                this.selectionMode === 'single' ? this.selectedFiles = [file] : this.selectedFiles.push(file);
            }
        },
        show(type, mode = 'multi') {
            this.selectedFiles = [];
            this.page = 1;
            this.visible = true;
            this.loading = false;
            this.formSearch.type = type ?? null;
            this.selectionMode = mode;
            this.getMedia();

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        submit() {
            this.visible = false;

            this.resolvePromise(this.selectionMode === 'single' ? this.selectedFiles[0] : this.selectedFiles);
        },
        cancel() {
            this.visible = false;
            this.resolvePromise(null);
        },
    },
    watch: {
        formSearch: {
            handler: throttle(function () {
               this.getMedia();
            }, 150),
            deep: true
        },
        'formSearch.title': {
            handler: throttle(function () {
                this.formSearch.page = 1;
            }, 150),
            deep: true
        },
        'formSearch.created': {
            handler: throttle(function () {
                this.formSearch.page = 1;
            }, 150),
            deep: true
        },
    }
}
</script>