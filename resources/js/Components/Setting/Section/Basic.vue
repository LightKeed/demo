<template>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <FormTextInput v-model="form.settings.app_url" v-model:error="form.errors['settings.app_url']" label="URL сайта" placeholder="Введите основной URL" id="app_url"/>
            <FormTextInput v-model="form.settings.app_name" v-model:error="form.errors['settings.app_name']" label="Имя сайта" placeholder="Введите имя" id="app_name"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 border-2 border-gray-300 rounded-lg bg-gray-100 p-4 dark:bg-gray-600 dark:border-gray-500">
            <FormMediaInput v-model="form.settings.logo" v-model:error="form.errors['settings.logo']" label="Логотип" type="image" mode="single" preview="true"/>
            <FormMediaInput v-model="form.settings.logo_dark" v-model:error="form.errors['settings.logo_dark']" label="Логотип (dark)" type="image" mode="single" preview="true"/>
        </div>
        <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
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
                    app_url: this.settings['app_url'] ?? null,
                    app_name: this.settings['app_name'] ?? null,
                    logo: this.settings['logo'] ?? null,
                    logo_dark: this.settings['logo_dark'] ?? null,
                }
            })
        }
    },
    methods: {
        update() {
            this.form.put(`/admin/settings/update`, {
                onSuccess: (res) => {
                    this.form.defaults({
                        settings: {
                            app_url: this.settings['app_url'] ?? null,
                            app_name: this.settings['app_name'] ?? null,
                            logo: this.settings['logo'] ?? null,
                            logo_dark: this.settings['logo_dark'] ?? null,
                        }
                    });

                    this.form.reset();
                },
            });
        },
    }
}
</script>