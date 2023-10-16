<template>
    <PageMediaModalShow ref="modalShow" :viewonly="true"/>

    <div class="mb-4">
        <div v-if="media.data && media.data.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
            <div
                v-for="file in media.data"
                :key="file"
                class="relative bg-gray-50 border-2 border-gray-300 rounded-lg overflow-visible hover:bg-slate-200 transition cursor-pointer dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:hover:bg-slate-500"
                :class="{ '!border-indigo-400': selectedFiles.find(f => f.id === file.id) }"
                @click="emit('select', file)"
            >
                <div v-if="selectedFiles.find(f => f.id === file.id)" class="absolute top-2 right-2 bg-indigo-400 rounded-lg text-white p-1 z-[10] pointer-events-none select-none">
                    <div class="flex items-center justify-center w-5 h-5 border-2 border-white rounded-full">
                        <span class="absolute text-[11px] font-medium">{{ selectedFiles.findIndex(f => f.id === file.id) + 1 }}</span>
                    </div>
                </div>
                <div class="group relative flex justify-center items-center overflow-hidden">
                    <img
                        v-if="file.type === 'image'"
                        :src="`/media/images/${file.path}?w=300&h=300`"
                        class="w-full h-[150px] object-cover select-none rounded-t-md"
                        alt="image"
                    >
                    <div v-else class="relative flex justify-center px-2 py-8">
                        <Icon v-if="file.type === 'document'" name="file-description" class="w-[80px] h-[80px] stroke-1"/>
                        <Icon v-else-if="file.type === 'audio'" name="volume" class="w-[80px] h-[80px] stroke-1"/>
                        <Icon v-else-if="file.type === 'video'" name="video" class="w-[80px] h-[80px] stroke-1"/>
                        <Icon v-else-if="file.type === 'table'" name="table" class="w-[80px] h-[80px] stroke-1"/>
                        <Icon v-else-if="file.type === 'archive'" name="file-zip" class="w-[80px] h-[80px] stroke-1"/>
                        <Icon v-else name="file" class="w-[80px] h-[80px] stroke-1"/>
                    </div>
                    <Tooltip :title="file.fullname" class="!top-auto !bg-white/80 overflow-hidden pointer-events-none !whitespace-normal break-all"/>
                </div>
                <div class="p-2 text-xs font-medium flex justify-between gap-1">
                    <div>
                        <div class="w-fit relative group flex items-center justify-center gap-1 mb-1">
                            <Icon name="server" size="5" class="min-w-[20px]"/>
                            {{ getFileSize(file.size) }}
                            <Tooltip title="Размер"/>
                        </div>
                        <div class="w-fit relative group flex items-center justify-center gap-1">
                            <Icon name="calendar-time" size="5" class="min-w-[20px]"/>
                            {{ new Date(file.created_at).toLocaleString() }}
                            <Tooltip title="Дата создания"/>
                        </div>
                    </div>
                    <div @click="showInfo(file)" class="group relative h-max flex justify-center bg-slate-400 hover:bg-slate-500 transition rounded-lg text-white p-1 z-[10] cursor-pointer">
                        <Icon name="info-circle" size="4"/>
                        <Tooltip title="Информация"/>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex items-center justify-center gap-1 p-4 bg-gray-50 border-2 border-gray-300 rounded-lg">
            <Icon name="folder"/>
            Файлы не найдены
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    media: Object,
    selectedFiles: [Object, Array]
})

const emit = defineEmits(['select'])
const modalShow = ref(null);

function showInfo(file) {
    modalShow.value.show(file);
}
</script>