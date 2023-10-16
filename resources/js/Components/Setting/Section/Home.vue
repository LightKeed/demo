<template>
    <div>
        <div class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Блок «Полезное»
        </div>
        <div>
            <button @click="addColumn" class="w-fit block mb-6 px-2 py-1 bg-gray-500 hover:bg-gray-700 rounded-md text-white text-xs cursor-pointer transition">Добавить колонку</button>
        </div>
        <div class="grid grid-cols-3 border-2 border-gray-300 rounded-xl p-8 gap-9">
            <div
                v-for="(item, index) in form.settings.helpful"
                :key="item"
                class="group/column relative grid gap-9 hover:bg-slate-100 transition"
                :class="getColumnClass(item)"
            >
                <div class="absolute top-0 right-0 flex items-center gap-2 opacity-0 group-hover/column:opacity-100 transition z-10">
                    <IconBtn v-if="item.colspan < 3" @click="addColspan(item)" type="button" name="columns-2" size="4" variation="success" title="Добавить колонку" class="opacity-50 hover:opacity-100"/>
                    <IconBtn v-if="item.colspan > 1" @click="delColspan(item)" type="button" name="columns-2" size="4" variation="danger" title="Удалить колонку" class="opacity-50 hover:opacity-100"/>
                    <IconBtn v-if="item.gridcols == 1" @click="changeGrid(item)" type="button" name="hexagon-number-1" size="4" variation="main" title="Изменить сетку" class="opacity-50 hover:opacity-100"/>
                    <IconBtn v-if="item.gridcols == 2" @click="changeGrid(item)" type="button" name="hexagon-number-2" size="4" variation="main" title="Изменить сетку" class="opacity-50 hover:opacity-100"/>
                    <IconBtn @click="addBlock(item)" type="button" name="plus" size="4" variation="success" title="Добавить блок" class="opacity-50 hover:opacity-100"/>
                    <IconBtn @click="removeColumn(index)" type="button" name="x" size="4" variation="danger" title="Удалить" class="opacity-50 hover:opacity-100"/>
                </div>
                <div
                    v-for="(block, indexBlock) in item.blocks"
                    :key="block"
                    :class="getBlockClass(block)"
                    class="group/block relative hover:bg-blue-100 transition"
                    :style="{ 'background': block.bgcolor, color: block.textcolor }"
                >
                    <div class="space-y-8">
                        <img class="object-cover w-full" :src="`/media/images/${block.media_id}`" alt="helpful" :style="{ height: `${block.media_height}px`}">
                        <div class="space-y-5" :class="{ 'px-8': block.bgcolor }">
                            <p class="text-2xl font-medium">{{ block.name }}</p>
                            <div class="grid gap-x-9 gap-y-1" :class="getBlockLinkClass(block)">
                                <a
                                    v-for="link in block.link"
                                    :key="link"
                                    :href="link.href"
                                    class="block hover:underline cursor-pointer"
                                >
                                    {{ link.title }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="absolute w-fit h-fit top-0 bottom-0 right-0 left-0 m-auto flex items-center gap-2 opacity-0 group-hover/block:opacity-100 transition">
                        <IconBtn v-if="indexBlock > 0" @click="upBlock(indexBlock, item)" type="button" name="chevron-up" size="4" variation="main" title="Вверх" class="opacity-50 hover:opacity-100"/>
                        <IconBtn v-if="indexBlock != (item.blocks.length ? item.blocks.length - 1 : 0)" @click="downBlock(indexBlock, item)" type="button" name="chevron-down" size="4" variation="main" title="Вниз" class="opacity-50 hover:opacity-100"/>
                        <IconBtn v-if="block.colspan == 1" @click="changeGridBlock(block)" type="button" name="square-number-1" size="4" variation="main" title="Изменить сетку" class="opacity-50 hover:opacity-100"/>
                        <IconBtn v-if="block.colspan == 2" @click="changeGridBlock(block)" type="button" name="square-number-2" size="4" variation="main" title="Изменить сетку" class="opacity-50 hover:opacity-100"/>
                        <IconBtn @click="changeTextcolorBlock(block)" type="button" name="typography" size="4" variation="main" title="Цвет текста" class="opacity-50 hover:opacity-100"/>
                        <div class="relative flex justify-center">
                            <div class="w-[100px] absolute bottom-full mb-2 flex items-center flex-wrap border border-gray-300 shadow rounded-lg p-2 bg-white gap-1">
                                <div @click="setBackgroundColor(block, color)" :title="color" v-for="color in colors" :key="color" class="w-[16px] h-[16px] rounded flex-none cursor-pointer" :style="{ background: color }"></div>
                            </div>
                            <IconBtn type="button" name="palette" size="4" variation="main" title="Цвет фона" class="opacity-50 hover:opacity-100"/>
                        </div>
                        <IconBtn @click="removeBlock(indexBlock, item)" type="button" name="x" size="4" variation="danger" title="Удалить" class="opacity-50 hover:opacity-100"/>
                    </div>
                </div>
                <div v-if="!item.blocks || item.blocks.length < 1" class="flex justify-center items-center font-medium">
                    Блоки отсутствуют
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        settings: [Object, Array],
    },
    data() {
        return {
            colors: [
                '#fff',
                '#0ea5e9', '#0284c7',
                '#60a5fa', '#3b82f6', '#2563eb', '#1d4ed8', '#1e40af'
            ],
            form: this.$inertia.form({
                settings: {
                    helpful: this.settings['helpful']
                }
            })
        }
    },
    mounted() {
        this.form.settings.helpful = JSON.parse(this.settings['helpful']);
    },
    methods: {
        getColumnClass(item) {
            return {
                'col-span-1': item.colspan == 1,
                'col-span-2': item.colspan == 2,
                'col-span-3': item.colspan == 3,
                'grid-cols-1': item.gridcols == 1,
                'grid-cols-2': item.gridcols == 2,
                'grid-cols-3': item.gridcols == 3,
            }
        },
        getBlockClass(block) {
            return {
                'col-span-1': block.colspan == 1,
                'col-span-2': block.colspan == 2,
                'row-span-1': block.rowspan == 1,
            }
        },
        getBlockLinkClass(block) {
            return {
                'grid-cols-1': block.colspan == 1,
                'grid-cols-2': block.colspan == 2
            }
        },
        addColumn() {
            this.form.settings.helpful.push({
                colspan: 1,
                gridcols: 1,
                blocks: []
            });
        },
        addColspan(item) {
            item.colspan = (+item.colspan !== 3) ? +item.colspan + 1 : +item.colspan;
        },
        delColspan(item) {
            item.colspan = (+item.colspan !== 1) ? +item.colspan - 1 : +item.colspan;
        },
        changeGrid(item) {
            item.gridcols = item.gridcols == 1 ? 2 : 1;
        },
        changeGridBlock(block) {
            block.colspan = block.colspan == 1 ? 2 : 1;
        },
        removeColumn(index) {
            this.form.settings.helpful.splice(index, 1);
        },
        addBlock(item) {
            if(Array.isArray(item.blocks)) {
                item.blocks.push({
                    name: 'Имя блока',
                    media_height: 150,
                    media_id: null,
                    colspan: null,
                    rowspan: 1,
                    textcolor: null,
                    bgcolor: null,
                    link: []
                });
            } else {
                item.blocks = [{
                    name: 'Имя блока',
                    media_height: 150,
                    media_id: null,
                    colspan: null,
                    rowspan: 1,
                    textcolor: null,
                    bgcolor: null,
                    link: []
                }];
            }
        },
        removeBlock(index, item) {
            item.blocks.splice(index, 1);
        },
        upBlock(index, item) {
            const blockMove = item.blocks[index];
            item.blocks.splice(index, 1);
            item.blocks.splice(index - 1, 0, blockMove);
        },
        downBlock(index, item) {
            const blockMove = item.blocks[index];
            item.blocks.splice(index, 1);
            item.blocks.splice(index + 1, 0, blockMove);
        },
        changeTextcolorBlock(block) {
            block.textcolor = block.textcolor ? null : '#fff';
        },
        setBackgroundColor(block, color) {
            block.bgcolor = color == '#fff' ? null : color;
            console.log(bgcolor);
        },
        update() {
            this.form.put(`/admin/settings/update`, {
                onSuccess: (res) => {
                    this.form.defaults({

                    });

                    this.form.reset();
                },
            });
        },
    }
}
</script>