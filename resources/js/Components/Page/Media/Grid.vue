<template>
    <PageMediaModalShow ref="modalShow"/>
    <div v-if="files.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2">
        <div
            v-for="file in files"
            :key="file"
            class="relative bg-gray-50 border-2 border-gray-300 rounded-lg overflow-visible hover:bg-slate-200 transition cursor-pointer dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:hover:bg-slate-500"
            :class="{ '!border-indigo-400': selectedFiles.includes(file.id) }"
            @click="multipleSelect ? select(file.id) : showInfo(file)"
        >
            <div v-if="selectedFiles.includes(file.id)" class="absolute top-2 right-2 bg-indigo-400 rounded-lg text-white p-1 z-[10]">
                <Icon name="circle-check" size="5"/>
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
            <div class="p-2 text-xs font-medium">
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
        </div>
    </div>
    <div v-else class="flex items-center justify-center gap-1 p-4 bg-gray-50 border-2 border-gray-300 rounded-lg">
        <Icon name="folder"/>
        Файлы не найдены
    </div>
</template>

<script>
export default {
    props: {
        files: Array,
        multipleSelect: Boolean,
        selectedFiles: Array
    },
    methods: {
        showInfo(file) {
            this.$refs.modalShow.show(file);
        },
        select(id) {
            let index = this.selectedFiles.indexOf(id);

            if(index >= 0) {
                this.selectedFiles.splice(index, 1);
            } else {
                this.selectedFiles.push(id);
            }
        }
    }
}
</script>