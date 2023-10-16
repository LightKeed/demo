<template>
    <div>
        <ModalConfirm ref="modalConfirm"/>
        <FileManagerBody ref="fileManager"/>
        <div class="flex items-center gap-2 mb-4 font-medium dark:text-white">
            <IconBtn @click="select" name="photo" title="Выбрать"/>
            {{ label }}
            <IconBtn v-if="modelValue" @click="remove()" name="x" size="4" variation="empty" title="Удалить"/>
        </div>
        <PreviewImage v-if="preview && modelValue" :id="modelValue"/>
    </div>
</template>

<script>
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        label: {
            type: String,
            default: 'Изображение'
        },
        type: String,
        mode: String,
        preview: [Boolean, String],
        error: String,
        modelValue: [String, Number]
    },
    emits: ['update:modelValue'],
    methods: {
        async select() {
            const value = await this.$refs.fileManager.show(this.type, this.mode);

            if(value && this.modelValue !== value ) {
                this.$emit('update:modelValue', value.id ?? value);
            }
        },
        async remove() {
            if(await this.$refs.modalConfirm.show(null, 'Вы действительно хотите убрать это изображение?')) {
                this.$emit('update:modelValue', null);
            }
        }
    }
}
</script>