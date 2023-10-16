<template>
    <SettingEditJsonArray v-model="form.reserved_slug" label="Зарезервированные URL (slug)"/>
    <SettingEditJsonArrayObject v-model="form.settings_sections" label="Разделы настроек"/>
    <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
</template>

<script>
export default {
    props: {
        settings: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                reserved_slug: [],
                settings_sections: []
            })
        }
    },
    mounted() {
        this.getValues();
    },
    methods: {
        getValues() {
            this.form.reserved_slug = JSON.parse(this.settings.reserved_slug ?? '[]');
            this.form.settings_sections = [];

            const settings_sections = this.settings ? JSON.parse(this.settings['settings_sections'] ?? '[]') : [];

            settings_sections.forEach(item => {
                const pushed = [];

                Object.entries(item).forEach((val) => {
                    pushed.push({
                        key: val[0],
                        value: val[1]
                    });
                });

                this.form.settings_sections.push(pushed);
            });
        },
        update() {
            this.form.transform((data) => ({
                ...data,
                settings: {
                    reserved_slug: JSON.stringify(this.form.reserved_slug),
                    settings_sections: JSON.stringify(this.form.settings_sections.map(item => Object.fromEntries(Object.entries(item).map(([key, val]) => [val.key, val.value])))),
                }
            }))
            .put(`/admin/settings/update`, {
                onSuccess: (res) => {
                    this.form.reset();
                    this.getValues();
                },
            });
        },
    }
}
</script>