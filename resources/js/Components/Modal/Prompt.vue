<template>
    <ModalOverlay class="z-[1000000]" :class="{ 'invisible': !visible, 'visible': visible }">
        <div @click="cancel" class="fixed w-full h-full"></div>
        <div class="p-2 w-full max-w-[500px]">
            <transition name="fade" mode="out-in">
                <PageBlock v-if="visible" class="relative w-full">
                    <div class="flex items-center gap-2 border-b border-gray-300 text-base font-bold pb-2 mb-2">
                        <Icon name="checks" class="text-green-500"/>
                        {{ title }}
                    </div>
                    <FormTextInput v-model="result" :label="text" id="result"/>
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
export default {
    props: {
        defTitle: {
            type: String,
            default: 'Заполните поле'
        },
        defText: {
            type: String,
            default: 'Введите запрашиваемые данные'
        },
        confirmText: {
            type: String,
            default: 'Продолжить'
        },
        cancelText: {
            type: String,
            default: 'Отмена'
        }
    },
    data() {
        return {
            visible: false,
            title: null,
            text: null,
            result: null,
            resolvePromise: null,
            rejectPromise: null
        }
    },
    methods: {
        show(text, title) {
            this.text = text ?? this.defText;
            this.title = title ?? this.defTitle;
            this.result = null;
            this.visible = true;

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            })
        },
        submit() {
            this.visible = false;
            this.resolvePromise(this.result);
        },
        cancel() {
            this.visible = false;
            this.resolvePromise(null);
        },
    }
}
</script>