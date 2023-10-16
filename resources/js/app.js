import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Head, Link } from '@inertiajs/vue3';
import VueTablerIcons from "vue-tabler-icons";
import { createPinia } from 'pinia';
import Layout from './Layouts/AppLayout';

import { support } from '@/Mixins/support.js'
import { settings } from '@/Mixins/settings.js'
import { permissions } from '@/Mixins/permissions.js'

createInertiaApp({
    title: title => title ? `ТИУ - ${title}` : 'ТИУ',
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || Layout
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueTablerIcons)
            .use(createPinia())
            .component('Head', Head)
            .component('Link', Link)
            .mixin(support)
            .mixin(settings)
            .mixin(permissions)
            .mount(el);
    },
    progress: {
        color: '#4f46e5',
    },
});
