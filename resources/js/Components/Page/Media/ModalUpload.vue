<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="visible" @cancel="cancel">
        <PageTitle>Загрузка файлов</PageTitle>

        <IconBtn @click="cancel" name="x" title="Отмена" variation="empty" class="!absolute !top-4 !right-4"/>

        <div class="flex flex-wrap gap-2 mb-2">
            <MainBtn @click="$refs.inputFile.showPicker()">Выбрать файлы</MainBtn>
            <MainBtn v-if="form.files.length" @click="clear" variation="danger">Очистить</MainBtn>
        </div>
        <form @submit.prevent="submit">
            <div
                @dragover.prevent="dragover"
                @drop.prevent="dropFile"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 min-h-[150px] relative mb-4 p-2 bg-gray-50 border-2 border-gray-300 rounded-lg text-center z-[100] dark:bg-gray-600 dark:border-gray-500 dark:text-white"
            >
                <div
                    @dragleave="dragleave"
                    v-if="!form.files.length"
                    class="absolute top-0 left-0 h-full w-full flex flex-col items-center justify-center gap-2 bg-slate-200/30 z-[50]"
                    :class="{ '!bg-indigo-100': isDragging }"
                    @dblclick="$refs.inputFile.showPicker()"
                >
                    <Icon name="upload"/>
                    <span class="font-medium select-none">
                        Переместите файлы в область или<br>нажмите «Выбрать файлы»
                    </span>
                </div>
                <div
                    v-for="(file, key) in form.files"
                    :key="key"
                    class="group/item relative p-2 text-xs text-left rounded-lg hover:bg-slate-200 transition dark:hover:bg-slate-500"
                >
                    <div class="group absolute top-2 right-2 flex justify-center invisible group-hover/item:visible">
                        <Icon @click="detachFile(key)" name="x" size="4" class="cursor-pointer"/>
                        <Tooltip title="Открепить"/>
                    </div>
                    <Icon name="file-import" class="w-[60px] h-[60px] stroke-1 mb-2"/>
                    <p class="mb-1 break-all">{{ file.name }}</p>
                    <p class="font-medium">{{ getFileSize(file.size) }}</p>
                </div>
            </div>
            <input @change="selectFiles" ref="inputFile" type="file" class="hidden" id="upload-files" multiple>
            <PageGroupBtn>
                <MainBtn @click="cancel" type="button" variation="second">Отмена</MainBtn>
                <MainBtn v-if="this.hasAccess('media_create')" type="submit" :loading="form.processing">Загрузить</MainBtn>
            </PageGroupBtn>
        </form>
    </ModalWindow>
</template>

<script>
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    data() {
        return {
            visible: false,
            isDragging: false,
            form: this.$inertia.form({
                files: []
            })
        }
    },
    methods: {
        show() {
            this.visible = true;
        },
        cancel() {
            this.form.reset();
            this.form.files = [];
            this.isDragging = false;
            this.visible = false;
        },
        clear() {
            this.form.files = [];
        },
        selectFiles(e) {
            this.attachFiles(Array.from(e.target.files));
            this.$refs.inputFile.value = null
        },
        attachFiles(files) {
            files.forEach(file => {
                this.form.files.push(file);
            });

            this.isDragging = false;
        },
        detachFile(index) {
            this.form.files.splice(index, 1);
        },
        dragover(e) {
            e.stopPropagation();

            if(e.dataTransfer.types.length) {
                this.isDragging = true;
            }
        },
        dragleave() {
            this.isDragging = false;
        },
        dropFile(e) {
            this.attachFiles(Array.from(e.dataTransfer.files));
            this.isDragging = false;
        },
        submit() {
            this.form.post('/admin/media', {
                onSuccess: (res) => {
                    this.cancel();
                },
                onError: (res) => {
                    notificationStore().add('error', res[0], 5000);
                }
            });
        }
    }
}
</script>
