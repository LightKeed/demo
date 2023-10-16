<template>
    <ModalConfirm ref="modalConfirm"/>
    <ModalWindow :show="visible" @cancel="close">
        <PageTitle>Информация о файле</PageTitle>

        <IconBtn @click="close" name="x" title="Закрыть" variation="empty" class="!absolute !top-4 !right-4"/>

        <form @submit.prevent="update">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="col-span-1 md:col-span-2 flex items-center justify-center p-2 bg-gray-50 border-2 border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500">
                    <img
                        v-if="file.type === 'image'"
                            :src="`/media/images/${file.path}`"
                        class="w-full max-h-[400px] object-contain select-none rounded-t-md"
                        alt="image"
                    >
                    <div v-else class="relative flex justify-center px-2 py-8">
                        <Icon v-if="file.type === 'document'" name="file-description" class="w-[150px] h-[150px] stroke-1"/>
                        <Icon v-else-if="file.type === 'audio'" name="volume" class="w-[150px] h-[150px] stroke-1"/>
                        <Icon v-else-if="file.type === 'video'" name="video" class="w-[150px] h-[150px] stroke-1"/>
                        <Icon v-else-if="file.type === 'table'" name="table" class="w-[150px] h-[150px] stroke-1"/>
                        <Icon v-else-if="file.type === 'archive'" name="file-zip" class="w-[150px] h-[150px] stroke-1"/>
                        <Icon v-else name="file" class="w-[150px] h-[150px] stroke-1"/>
                    </div>
                </div>
                <div class="col-span-0">
                    <PageTitle level="2">Информация о файле</PageTitle>
                    <div class="leading-5 text-xs mb-2 pb-2 border-b border-indigo-500 dark:text-white dark:border-indigo-400">
                        <p><span class="font-medium">Создано:</span> {{ new Date(file.created_at).toLocaleString() }}</p>
                        <p><span class="font-medium">Владелец:</span> {{ file.owner.surname ?? '' }} {{ file.owner.name ?? '' }} {{ file.owner.patronymic ?? '' }}</p>
                        <p><span class="font-medium">Имя файла:</span> {{ file.fullname }}</p>
                        <p><span class="font-medium">Размер файла:</span> {{ getFileSize(file.size) }}</p>
                        <p><span class="font-medium">Тип файла:</span> {{ file.type ?? 'Не определен' }}</p>
                        <p><span class="font-medium">Расширение:</span> {{ file.extension ?? '-' }}</p>
                    </div>
                    <FormTextareaInput class="mb-2" v-model="form.description" v-model:error="form.errors.description" label="Описание" placeholder="Описание файла" id="file-description" :disabled="file.deleted_at || viewonly"/>
                    <FormTextInput label="Ссылка на файл" v-model="fileURL" id="file-url" disabled readonly/>
                    <MainBtn @click="copyLink" class="text-xs !px-2 !py-1" type="button">Скопировать URL</MainBtn>
                </div>
            </div>
            <PageGroupBtn>
                <MainBtn @click="close" type="button" variation="second">Закрыть</MainBtn>
                <div v-if="!viewonly" class="flex flex-wrap items-center justify-end gap-2">
                    <MainBtn v-if="this.hasAccess('media_create')" @click="destroy(this.form.id)" variation="danger" type="button">Удалить</MainBtn>
                    <MainBtn v-if="this.hasAccess('media_edit') && !file.deleted_at" @click="update" type="submit" :loading="form.processing">Сохранить</MainBtn>
                    <MainBtn v-else-if="this.hasAccess('media_restore')" @click="restore(this.form.id)" type="button" variation="other">Восстановить</MainBtn>
                </div>
            </PageGroupBtn>
        </form>
    </ModalWindow>
</template>

<script>
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        viewonly: Boolean
    },
    data() {
        return {
            visible: false,
            file: null,
            fileURL: null,
            form: this.$inertia.form({
                id: null,
                description: null,
                alt: null
            })
        }
    },
    methods: {
        show(file) {
            this.file = file;
            this.fileURL = `${this.getLocation()}/media/${file.path}`;
            this.form.id = file.id;
            this.form.description = file.description;

            this.visible = true;
        },
        close() {
            this.file = null;
            this.form.reset();

            this.visible = false;
        },
        getLocation() {
            return window.location.origin;
        },
        copyLink() {
            notificationStore().add('info', 'Ссылка скопирована', 2500);
            navigator.clipboard.writeText(this.fileURL);
        },
        update() {
            this.form.put(`/admin/media/${this.form.id}`, {
                onSuccess: (res) => {
                    this.close();
                },
            });
        },
        async restore(id) {
            if(await this.$refs.modalConfirm.show('restore', 'Вы действительно хотите восстановить этот файл?')) {
                this.$inertia.post(`/admin/media/${id}/restore`, {}, {
                    onSuccess: (res) => {
                        this.close();
                    },
                });
            }
        },
        async destroy(id) {
            if(await this.$refs.modalConfirm.show('delete', 'Вы действительно хотите удалить этот файл?')) {
                this.$inertia.delete(`/admin/media/${id}`, {
                    onSuccess: () => {
                        this.close();
                    }
                });
            }
        }
    }
}
</script>