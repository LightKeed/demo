<template>
    <ModalOverlay class="z-[1000000]" :class="{ 'invisible': !visible, 'visible': visible }">
        <div @click="cancel" class="fixed w-full h-full"></div>
        <div class="p-2 w-full max-w-[500px]">
            <transition name="fade" mode="out-in">
                <PageBlock v-if="visible" class="relative w-full">
                    <template v-if="step === 0">
                        <div class="flex items-center gap-2 border-b border-gray-300 text-base font-bold pb-2">
                            <Icon name="checks" class="text-green-500"/>
                            {{ title }}
                        </div>
                        <div class="text-sm py-2 mb-2">
                            {{ text }}
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <MainBtn @click="cancel" variation="second">{{ cancelText }}</MainBtn>
                            <MainBtn @click="nextStep">{{ nextStepText }}</MainBtn>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex items-center gap-2 border-b border-gray-300 text-base font-bold pb-2">
                            <Icon name="lock" class="text-gray-800"/>
                            Введите пароль
                        </div>
                        <div class="text-sm py-2 mb-2">
                            Чтобы продолжить необходимо ввести действительный пароль.
                        </div>
                        <FormTextInput v-model="password" label="Подтверждение пароля" placeholder="Введите пароль" type="password" class="mb-4"/>
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <MainBtn @click="cancel" variation="second">{{ cancelText }}</MainBtn>
                            <MainBtn @click="confirm">{{ confirmText }}</MainBtn>
                        </div>
                    </template>
                </PageBlock>
            </transition>
        </div>
    </ModalOverlay>
</template>

<script>
import { useNotificationStore as notificationStore } from '@/Stores/notification.js';

export default {
    props: {
        defTitle: {
            type: String,
            default: 'Подтвердите действие'
        },
        defText: {
            type: String,
            default: 'Вы действительно хотите совершить это действие?'
        },
        nextStepText: {
            type: String,
            default: 'Продолжить'
        },
        confirmText: {
            type: String,
            default: 'Подтвердить'
        },
        cancelText: {
            type: String,
            default: 'Отмена'
        }
    },
    data() {
        return {
            visible: false,
            step: 0,
            title: null,
            text: null,
            resolvePromise: null,
            rejectPromise: null,
            password: null
        }
    },
    methods: {
        show(text, title) {
            this.text = text ?? this.defText;
            this.title = title ?? this.defTitle;
            this.visible = true;
            this.step = 0;
            this.password = null;

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        confirm() {
            const password = this.password;

            if(!password) {
                notificationStore().add('warning', 'Необходимо ввести пароль!', 2500);
            } else {
                this.password = null;
                this.visible = false;
                this.resolvePromise(password);
            }
        },
        nextStep() {
            this.step = 1;
        },
        cancel() {
            this.visible = false;
            this.resolvePromise(false);
        },
    }
}
</script>