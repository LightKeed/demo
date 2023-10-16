<template>
    <ModalOverlay class="z-[1000000]" :class="{ 'invisible': !visible, 'visible': visible }">
        <div @click="cancel" class="fixed w-full h-full"></div>
        <div class="p-2 w-full max-w-[480px]">
            <transition name="fade" mode="out-in">
                <PageBlock v-if="visible" class="relative w-full">
                    <div class="flex items-center gap-2 border-b border-gray-300 text-base font-bold pb-2 mb-2">
                        <Icon name="icons" class="text-blue-600"/>
                        {{ title }}
                    </div>

                    <FormTextInput v-model="search" placeholder="Поиск по названию" id="result" class="mb-4"/>

                    <div class="flex flex-wrap items-center gap-2 mb-4 overflow-y-auto max-h-[275px]">
                        <template v-if="search">
                            <a v-for="(icon, index) in icons.filter(el => el.includes(search))" :key="index" @click="pick(icon)" :title="icon" class="w-fit block text-gray-400 hover:text-blue-600 transition cursor-pointer" :class="{ '!text-blue-600': icon === result }">
                                <Icon :name="`${icon}`" size="5"/>
                            </a>
                        </template>
                        <template v-else v-for="(icon, index) in icons" :key="index">
                            <template v-if="index < limit">
                                <a @click="pick(icon)" :title="icon" class="w-fit block text-gray-400 hover:text-blue-600 transition cursor-pointer" :class="{ '!text-blue-600': icon === result }">
                                    <Icon :name="`${icon}`" size="5"/>
                                </a>
                            </template>
                        </template>
                    </div>

                    <a v-if="!search && count !== limit" @click="loadIcons()" class="w-fit block mx-auto mb-6 px-2 py-1 bg-gray-500 hover:bg-gray-700 rounded-md text-white text-xs cursor-pointer transition">
                        Загрузить больше
                    </a>

                    <div v-if="result" class="flex items-center justify-center gap-2 font-medium mb-8 text-sm">
                        Выбрано:
                        <Icon id="currentIcon" :name="result" size="6" class="text-blue-600"/>
                    </div>

                    <div class="flex flex-wrap iteQms-center justify-between gap-2">
                        <MainBtn @click="cancel" variation="second">{{ cancelText }}</MainBtn>
                        <MainBtn @click="submit">
                            {{ confirmText }}
                        </MainBtn>
                    </div>
                </PageBlock>
            </transition>
        </div>
    </ModalOverlay>
</template>

<script>
import iconData from '@/Configs/icons.js';
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        defTitle: {
            type: String,
            default: 'Выберите иконку'
        },
        confirmText: {
            type: String,
            default: 'Выбрать'
        },
        cancelText: {
            type: String,
            default: 'Отмена'
        }
    },
    data() {
        return {
            search: null,
            icons: [],
            limit: 100,
            count: 0,
            visible: false,
            title: null,
            result: null,
            resultSVG: null,
            resolvePromise: null,
            rejectPromise: null
        }
    },
    methods: {
        pick(icon) {
            this.result = this.result === icon ? null : icon;
        },
        loadIcons() {
            if(this.limit + 100 > this.count) {
                this.limit = this.count;
            } else {
                this.limit += 100;
            }
        },
        show(title) {
            this.icons = iconData;
            this.count = this.icons.length;
            this.search = null;
            this.title = title ?? this.defTitle;
            this.result = null;
            this.visible = true;

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        submit() {
            if(!this.result) {
                notificationStore().add('warning', 'Необходимо выбрать иконку', 5000);
            } else {
                this.resultSVG = document.getElementById('currentIcon');

                this.visible = false;
                this.resolvePromise({
                    'icon': this.result,
                    'svg': this.resultSVG
                });

                this.result = null;
                this.limit = 100;
                this.count = 0;
            }
        },
        cancel() {
            this.result = null;
            this.limit = 100;
            this.count = 0;
            this.visible = false;
            this.resolvePromise(null);
        },
    }
}
</script>