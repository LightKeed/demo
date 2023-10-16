<template>
    <div>
        <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
        <div class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Информация о PHP
        </div>
        <div id="container-phpinfo" class="w-fit max-h-[400px] mx-auto overflow-y-auto"></div>
    </div>
</template>

<script>
export default {
    props: {
        settings: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                settings: {

                }
            })
        }
    },
    async mounted() {
        let response = await fetch('/admin/settings/php');
        const phpinfo = await response.text();

        const containerPhp = document.getElementById("container-phpinfo");
        const divInfo = document.createElement('div');
        divInfo.insertAdjacentHTML('beforeend', phpinfo);
        containerPhp.attachShadow({ mode: 'open' }).appendChild(divInfo);
    },
    methods: {
        update() {
            this.form.put(`/admin/settings/update`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        settings: {

                        }
                    });

                    this.form.reset();
                },
            });
        },
    }
}
</script>