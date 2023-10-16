import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        items: []
    }),

    actions: {
        uuid() {
            return Math.random().toString(16).slice(2)+(new Date()).getTime()+Math.random().toString(16).slice(2);
        },
        add(type, text, delay) {
            this.items.push({
                id: this.uuid(),
                type: type,
                text: text,
                delay: delay
            });
        }
    },
})