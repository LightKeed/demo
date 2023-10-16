<template>
    <SettingEditJsonObject v-model="form.contacts" label="Контакты"/>
    <SettingEditJsonObject v-model="form.acceptance_contacts" label="Контакты приемной компании"/>
    <SettingEditJsonArrayObject v-model="form.social" label="Социальные сети"/>
    <SettingEditJsonObjectArray v-model="form.service_link" label="Сервисы (навигация)"/>

    <MainBtn @click="update" :loading="form.processing">Сохранить</MainBtn>
</template>

<script>
export default {
    props: {
        settings: [Object, Array]
    },
    data() {
        return {
            form: this.$inertia.form({
                contacts: [],
                acceptance_contacts: [],
                social: [],
                service_link: []
            })
        }
    },
    mounted() {
        this.getValues();
    },
    methods: {
        getValues() {
            this.form.contacts = [];
            this.form.acceptance_contacts = [];
            this.form.social = [];
            this.form.service_link = [];

            const contacts = this.settings ? JSON.parse(this.settings['contacts'] ?? '[]') : [];
            const acceptance_contacts = this.settings ? JSON.parse(this.settings['acceptance_contacts'] ?? '[]') : [];
            const social = this.settings ? JSON.parse(this.settings['social'] ?? '[]') : [];
            const service_link = this.settings ? JSON.parse(this.settings['service_link'] ?? '[]') : [];

            Object.entries(contacts).forEach((val) => {
                this.pushValue('contacts', val[0], val[1]);
            });

            Object.entries(acceptance_contacts).forEach((val) => {
                this.pushValue('acceptance_contacts', val[0], val[1]);
            });

            social.forEach(item => {
                const pushed = [];

                Object.entries(item).forEach((val) => {
                    pushed.push({
                        key: val[0],
                        value: val[1]
                    });
                });

                this.form.social.push(pushed);
            });

            Object.entries(service_link).forEach(item => {
                const value = [];

                item[1].forEach(arr => {
                    const arrValue = [];

                    Object.entries(arr).forEach((val) => {
                        arrValue.push({
                            key: val[0],
                            value: val[1]
                        });
                    });

                    value.push(arrValue);
                });

                this.form.service_link.push({
                    key: item[0],
                    value: value
                });
            });
        },
        pushValue(variable, key, value) {
            this.form[variable].push({
                type: typeof value === 'object',
                key: key,
                value: value
            });
        },
        update() {
            this.form.transform((data) => ({
                ...data,
                settings: {
                    contacts: JSON.stringify(Object.fromEntries(Object.entries(this.form.contacts).map(([key, val]) => [val.key, val.value]))),
                    acceptance_contacts: JSON.stringify(Object.fromEntries(Object.entries(this.form.acceptance_contacts).map(([key, val]) => [val.key, val.value]))),
                    social: JSON.stringify(this.form.social.map(item => Object.fromEntries(Object.entries(item).map(([key, val]) => [val.key, val.value])))),
                    service_link: JSON.stringify(this.form.service_link.reduce((acc, curr) => {acc[curr.key] = curr.value.map(item => Object.fromEntries(Object.entries(item).map(([key, val]) => [val.key, val.value])));return acc;}, {}))
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