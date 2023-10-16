<template>
    <FileManagerBody ref="fileManager"/>
    <PersonalCardBody ref="personalCard"/>
    <ModalPromptSelect ref="modalPromptSelect"/>
    <ModalPickIcon ref="modalPickIcon"/>
    <!-- <button @click="onSave">SAVE</button> -->
    <div class="p-4 border-2 border-gray-300 rounded-lg dark:text-white">
        <div id="editorjs"/>
    </div>
    <PageTitle class="mt-4">Отладка редактора</PageTitle>
    <div class="max-h-[600px] bg-gray-50 p-4 border-2 border-gray-300 rounded-lg break-all overflow-y-auto dark:bg-gray-700 dark:text-white">
        <pre class="whitespace-pre-wrap">{{ JSON.stringify(store.data, undefined, 2) }}</pre>
    </div>
</template>

<script>
import axios from 'axios';
import EditorJS from '@editorjs/editorjs';
import { useStoreEditor as editorStore } from "@/Stores/editor.js";
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

import MainTools from './Configs/MainTools.js'
import Russian from './Configs/Russian.js'


export default {
    data() {
        return {
            store: editorStore()
        }
    },
    mounted() {
        const showFileManager = async (type) => {
            return await this.$refs.fileManager.show(type);
        }

        const showPersonalCard = async () => {
            return await this.$refs.personalCard.show();
        }

        const showPickIcon = async () => {
            return await this.$refs.modalPickIcon.show();
        }

        const uploadFile = async (file, type = []) => {
            return await axios.post('/admin/media/upload', { files: [file], type: type }, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(res => {
                    notificationStore().add('success', 'Файл успешно загружен и прикреплен', 2500);
                    return res.data;
                })
                .catch(res => {
                    notificationStore().add('error', 'При загрузке файла произошла ошибка', 2500);
                });
        }

        const selectThematicSection = async () => {
            const section = await axios.get('/admin/thematic-sections/all')
                .then(async res => {
                    const data = res.data;
                    const items = [];

                    data.forEach(item => {
                        items.push({
                            id: item.id,
                            title: `${item.name}` + (item.parent ? `(${item.parent.name})` : '')
                        });
                    })

                    return await this.$refs.modalPromptSelect.show('Выберите тематический раздел новости', null, data);
                })
                .catch(res => {
                    notificationStore().add('error', 'При получении списка новостей произошла ошибка', 2500);
                });

            return section;
        }

        window.showFileManager = showFileManager;
        window.showPersonalCard = showPersonalCard;
        window.showPickIcon = showPickIcon;
        window.uploadFile = uploadFile;
        window.selectThematicSection = selectThematicSection;

        const editor = new EditorJS({
            holder: 'editorjs',
            minHeight: 50,
            tools: MainTools,
            i18n: Russian,
            logLevel: 'ERROR',
            onChange: (event) => {
                onSave();
            }
        });

        editor.isReady
            .then(() => {
                if (this.store.data.blocks && this.store.data.blocks.length) {
                    editor.render(this.store.data);
                }

                //onSave();
            })
            .catch((reason) => {
                console.log(`Editor.js initialization failed because of ${reason}`)
            });

        const onSave = async () => {
            const outputData = await editor.save();

            this.store.data = outputData;
        }
    }
}
</script>

<style>
#editorjs {
    @apply text-lg;
}

.codex-editor__redactor {
    @apply !p-0;
}

h2[class~=ce-header] {
    @apply font-normal text-4xl leading-tight;
}

h3[class~=ce-header] {
    @apply font-normal text-3xl leading-tight;
}

h4[class~=ce-header] {
    @apply text-2xl font-medium;
}

.ce-editorjsColumns_wrapper {
    @apply my-6;
}

.ce-block__content,
.ce-toolbar__content {
    max-width: calc(100% - 100px) !important;

}

.cdx-block {
    max-width: 100% !important;
}

.editorjs-wrapper {
    border: 1px solid #eee;
    border-radius: 5px;
    padding: 0px;
    margin-bottom: 10px;
    box-shadow: 0 6px 18px #e8edfa80;
}

.ce-editorjsColumns_col {
    border: 1px solid #eee;
    border-radius: 5px;
    gap: 10px;
    padding: 10px 0;
}

.ce-editorjsColumns_col .ce-block__content {
  max-width: 100% !important;
  margin: 0 70px 0 15px;
}

.ce-editorjsColumns_col .ce-toolbar__actions{
  right: -45px;
}

.ce-editorjsColumns_col:focus-within {
    box-shadow: 0 6px 18px #e8edfa80;
}

.ce-editorjsColumns_col .codex-editor--narrow .codex-editor__redactor {
  margin-right: 0 !important;
}

.ce-block__content div[contenteditable="true"] {
  outline: none;
}

.ce-editorjsColumns_col .ce-toolbar__actions {
  flex-direction: row-reverse;
}

.ce-toolbar {
  z-index: 9999 !important;
}

.wdd-spoiler-wrapper {
  z-index: 0 !important;
  position: relative;
}

.ce-block__content {
  z-index: 0 !important;
  position: relative;
}

.ce-block--focused .wdd-spoiler-wrapper {
  z-index: 1 !important;
  position: relative !important;
}

.ce-block--focused .ce-block__content {
  z-index: 1 !important;
  position: relative !important;
}

.codex-editor {
    z-index: 1 !important;
}

.codex-editor--toolbox-opened {
    z-index: 2 !important;
}

.ce-editorjsColumns_col .codex-editor {
    z-index: 0 !important;
}

.wdd-text-wrapper {
  @apply flex items-center;
}

.wdd-icon {
  @apply flex-none;

  padding-top: 7px;
}

.wdd-text {
  line-height: 1.6em;
  padding: 0.4em 0;
  outline: none;
  width: 100%;
  /* background-color: #0091DB; */
  /* color:#fff; */
  /* padding: 10px; */
  /* margin : 15px; */
}
</style>
