<template>
    <div class="fixed top-0 right-0 p-2 right-[5px] w-full max-w-[400px] flex flex-col gap-3 z-[10000000]">
        <transition-group name="slide-fade">
            <template v-for="(notification, index) in reversedItems" :key="notification">
                <div v-if="index < 5" class="w-full text-gray-500 bg-white rounded-lg shadow-xl border border-gray-300 overflow-hidden dark:bg-gray-800 dark:border-gray-500 dark:text-white" role="alert">
                    <div class="flex items-center gap-1 p-4">
                        <div v-if="notification.type === 'info'" class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-blue-500 bg-blue-100 rounded-lg">
                            <Icon name="flame" size="5"/>
                        </div>
                        <div v-else-if="notification.type === 'success'" class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                            <Icon name="check" size="5"/>
                        </div>
                        <div v-else-if="notification.type === 'warning'" class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                            <Icon name="alert-triangle" size="5"/>
                        </div>
                        <div v-else-if="notification.type === 'error'" class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                            <Icon name="x" size="5"/>
                        </div>
                        <div v-else class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-gray-500 bg-gray-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                            <Icon name="info-circle" size="5"/>
                        </div>
                        <div class="ml-2 text-sm font-normal">{{ notification.text }}</div>
                        <button @click="close(notification.id)" type="button" class="group relative flex justify-center self-baseline ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:bg-gray-600 dark:text-white dark:focus:ring-gray-400" data-dismiss-target="#toast-default" aria-label="Close">
                            <Icon name="x" size="5"/>
                            <Tooltip title="Закрыть" position="left"/>
                        </button>
                    </div>
                    <div class="bg-slate-400 h-[4px] flex flex-row-reverse">
                        <div class="bg-blue-500 h-[4px]" :style="{ width: 100 - ((notification.delay - notification.bar) * 100) / notification.delay + '%' }"></div>
                    </div>
                </div>
            </template>
        </transition-group>
    </div>
</template>

<script>
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    data() {
        return {
            notifications: [],
            store: notificationStore()
        }
    },
    computed: {
        reversedItems() {
            return [...this.notifications].reverse();
        },
    },
    methods: {
        currentRemainingTime(delay) {
            setInterval(() => {
                return 100;
            }, 10);

            return delay;
        },
        close(id) {
            const index = this.store.items.findIndex(n => n.id === id);
            const localIndex = this.store.items.findIndex(n => n.id === id);

            if(index >= 0) {
                this.store.items.splice(index, 1);
                this.notifications.splice(localIndex, 1);
            }
        }
    },
    watch: {
        'store.items': {
            handler(val) {
                val.forEach(el => {
                    if(!this.notifications.find(n => n.id === el.id)) {
                        el.bar = el.delay;
                        this.notifications.push(el);

                        let timer = setInterval(() => {
                            el.bar = el.bar - 10;
                        }, 10);

                        setTimeout(() => {
                            clearInterval(timer);
                            this.close(el.id);
                        }, el.delay);
                    }
                });
            },
            deep: true
        }
    }
}
</script>