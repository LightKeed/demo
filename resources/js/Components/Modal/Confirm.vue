<template>
    <ModalOverlay class="z-[10000000000]" :class="{ 'invisible': !visible, 'visible': visible }">
        <div @click="cancel" class="fixed w-full h-full"></div>
        <div class="p-2 w-full max-w-[500px]">
            <transition name="fade" mode="out-in">
                <PageBlock v-if="visible" class="relative w-full">
                    <div class="flex items-center gap-2 border-b border-gray-300 text-base font-bold pb-2 dark:text-white">
                        <Icon name="checks" class="text-green-500"/>
                        {{ title }}
                    </div>
                    <div class="text-sm py-2 mb-2 dark:text-white">
                        {{ text }}
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <MainBtn @click="cancel" variation="second">{{ cancelText }}</MainBtn>
                        <MainBtn v-if="action === 'delete'" @click="confirm" variation="danger">
                            <Icon name="trash" size="5"/>
                            Удалить
                        </MainBtn>
                        <MainBtn v-else-if="action === 'restore'" @click="confirm" variation="other">
                            <Icon name="rotate-clockwise" size="5"/>
                            Восстановить
                        </MainBtn>
                        <MainBtn v-else @click="confirm">
                            {{ confirmText }}
                        </MainBtn>
                    </div>
                </PageBlock>
            </transition>
        </div>
    </ModalOverlay>
</template>

<script>
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
            action: null,
            title: null,
            text: null,
            resolvePromise: null,
            rejectPromise: null
        }
    },
    methods: {
        show(action, text, title) {
            this.action = action;
            this.text = text ?? this.defText;
            this.title = title ?? this.defTitle;
            this.visible = true;

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        confirm() {
            this.visible = false;
            this.resolvePromise(true);
        },
        cancel() {
            this.visible = false;
            this.resolvePromise(false);
        },
    }
}
</script>