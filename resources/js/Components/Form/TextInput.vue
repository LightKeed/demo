<template>
    <div class="mb-2 last:mb-0" :class="$attrs.class" :style="$attrs.style">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
        <div class="relative flex items-center">
            <input
                :id="id"
                ref="input"
                v-bind="{ ...$attrs, class: null, style: null }"
                class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:!border-blue-500 block w-full p-2.5 outline-none disabled:bg-slate-300 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                :class="{ 'pr-8': type === 'password' }"
                :type="inputType"
                :value="modelValue ?? $attrs.value"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <a v-if="type === 'password'" class="absolute cursor-pointer right-2" @click="showPassword">
                <svg v-if="inputType == 'password'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-slate-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 fill-slate-700">
                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <div v-if="desc" class="mt-1 text-xs text-gray-500 dark:text-gray-200">{{ desc }}</div>
        <div v-if="error" class="flex items-center gap-1 mt-1 text-xs text-red-500">
            <Icon @click="$emit('update:error', null);" name="circle-x" class="min-w-[20px] cursor-pointer" size="5"/>
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        type: {
            type: String,
            default: 'text',
        },
        label: String,
        desc: String,
        error: String,
        modelValue: [String, Number],
    },
    data() {
        return {
            inputType: this.type
        }
    },
    emits: ['update:modelValue', 'update:error'],
    methods: {
        showPassword() {
            this.inputType = this.inputType == 'password' ? 'text' : 'password'
        },
    }
}
</script>