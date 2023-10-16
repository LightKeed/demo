<template>
    <Link
        v-if="$page.props.auth.user"
        href="/admin"
        class="group block relative w-full flex items-center rounded-lg text-slate-600 hover:bg-indigo-400 hover:text-white transition cursor-pointer dark:text-white"
        :class="{ 'bg-slate-600 !text-white dark:bg-slate-400': $page.url === '/admin' }"
    >
        <div class=" min-w-[40px] w-[40px] h-[40px] flex items-center justify-center">
            <Icon name="home" size="5"/>
        </div>
        <span
            class="hidden whitespace-nowrap text-sm font-medium text-gray-900 p-1 rounded-lg left-full shadow-md z-[100] transition-transform select-none animate-[top_0.5s_both] duration-500 group-hover:block"
            :class="{
                '!text-white': $page.url === '/admin' && show,
                '!block bg-transparent shadow-none group-hover:text-white duration-100 dark:text-white': show,
                'absolute border border-gray-300 ml-1.5 hover:!hidden !bg-white': !show,
            }"
        >
            Главная
        </span>
    </Link>
    <template v-for="(link, key) in links" :key="key">
        <template v-if="link.access">
            <Link
                v-if="!link.items.length"
                :href="link.url"
                class="group block relative w-full flex items-center rounded-lg text-slate-600 hover:bg-indigo-400 hover:text-white transition cursor-pointer dark:text-white"
                :class="{ 'bg-slate-600 !text-white dark:bg-slate-400': $page.url.startsWith(link.url) }"
            >
                <div class="min-w-[40px] w-[40px] h-[40px] flex items-center justify-center">
                    <Icon :name="link.icon" size="5"/>
                </div>
                <span
                    class="hidden whitespace-nowrap text-sm font-medium text-gray-900 p-1 rounded-lg left-full shadow-md z-[100] transition-transform select-none animate-[top_0.5s_both] duration-500 group-hover:block"
                    :class="{
                        '!text-white': $page.url.startsWith(link.url) && show,
                        '!block bg-transparent shadow-none group-hover:text-white duration-100 dark:text-white': show,
                        'absolute border border-gray-300 ml-1.5 hover:!hidden !bg-white': !show,
                    }"
                >
                    {{ link.title }}
                </span>
            </Link>
            <a
                v-else
                @click="showItems(key)"
                class="group block relative w-full flex items-center rounded-lg text-slate-600 hover:bg-indigo-400 hover:text-white transition cursor-pointer dark:text-white"
                :class="{ 'bg-slate-600 !text-white dark:bg-slate-400': link.segments.some(segment => $page.url.startsWith(segment)) }"
            >
                <div class="min-w-[40px] w-[40px] h-[40px] flex items-center justify-center">
                    <Icon :name="link.icon" size="5"/>
                </div>
                <div
                    class="hidden ml-1.5 text-sm font-medium text-gray-900 p-1 rounded-lg left-full shadow-md z-[100] transition-transform select-none animate-[top_0.5s_both] duration-500 group-hover:block"
                    :class="{
                        '!text-white': link.segments.some(segment => $page.url.startsWith(segment)) && show,
                        'w-full !block bg-transparent shadow-none group-hover:text-white duration-100 dark:text-white': show,
                        'absolute border border-gray-300 !bg-white': !show,
                    }"
                >
                    <span class="flex items-center justify-between gap-1">
                        {{ link.title }}
                        <Icon v-if="show" name="chevron-down" size="5"/>
                    </span>
                </div>
            </a>
            <div
                v-if="link.items.length"
                :ref="`item-${key}`"
                class="h-0 !m-0 flex flex-col gap-1 pl-10 transition-[height] duration-500 overflow-hidden"
                :class="{ '!h-0 !duration-[0ms]': !show, 'delay-200': show }"
                :style="{ height: showItem === key ? heightItem + 'px' : '0px' }"
            >
                <template v-for="item in link.items" :key="item">
                    <Link
                        v-if="item.access"
                        :href="item.url"
                        class="h-[30px] first:mt-1 flex items-center gap-1 p-2 rounded-lg text-sm font-medium text-gray-900 hover:bg-indigo-400 hover:text-white transition whitespace-nowrap cursor-pointer dark:text-white"
                        :class="{ 'bg-slate-600 !text-white dark:bg-slate-400': $page.url.startsWith(item.url) }"
                    >
                        <Icon v-if="item.icon" :name="item.icon" size="4"/>
                        {{ item.title }}
                    </Link>
                </template>
            </div>
        </template>
    </template>
</template>

<script>
export default {
    props: {
        show: Boolean,
        navHeight: Number
    },
    emits: ['open', 'update:navHeight'],
    data() {
        return {
            showItem: null,
            heightItem: 0,
            links: [
                {
                    icon: 'key',
                    title: 'Авторизация',
                    url: '/admin/auth',
                    access: !this.$page.props.auth.user,
                    items: []
                },
                {
                    icon: 'list-details',
                    title: 'Разделы',
                    url: '/admin/categories',
                    access: this.hasAccess('category_view'),
                    items: []
                },
                {
                    icon: 'file-stack',
                    title: 'Страницы',
                    url: '/admin/articles',
                    access: this.hasAccess('article_view'),
                    items: []
                },
                {
                    icon: 'tags',
                    title: 'Теги',
                    url: '/admin/tags',
                    access: this.hasAccess('tag_view'),
                    items: []
                },
                {
                    icon: 'section',
                    title: 'Тематические разделы',
                    url: '/admin/thematic-sections',
                    access: this.hasAccess('thematic-section_view'),
                    items: []
                },
                {
                    icon: 'news',
                    title: 'Новости',
                    url: '/admin/news',
                    access: this.hasAccess('news_view'),
                    items: []
                },
                {
                    icon: 'calendar-event',
                    title: 'Мероприятия',
                    url: '/admin/events',
                    access: this.hasAccess('event_view'),
                    items: []
                },
                {
                    icon: 'message',
                    title: 'Комментарии',
                    url: '/admin/comments',
                    access: this.hasAccess('comment_view'),
                    items: []
                },
                {
                    icon: 'slideshow',
                    title: 'Слайдеры',
                    url: '/admin/sliders',
                    access: this.hasAccess('slider_view'),
                    items: []
                },
                {
                    icon: 'id',
                    title: 'Персонал',
                    url: '/admin',
                    segments: ['/admin/department-types', '/admin/employees', '/admin/addresses', '/admin/departments'],
                    access: this.hasAccess('personal_view'),
                    items: [
                        {
                            icon: 'clipboard-list',
                            title: 'Типы подразделений',
                            url: '/admin/department-types',
                            access: this.hasAccess('personal_view')
                        },
                        {
                            icon: 'building',
                            title: 'Подразделения',
                            url: '/admin/departments',
                            access: this.hasAccess('personal_view')
                        },
                        {
                            icon: 'users',
                            title: 'Сотрудники',
                            url: '/admin/employees',
                            access: this.hasAccess('personal_view')
                        },
                        {
                            icon: 'map-pins',
                            title: 'Адреса',
                            url: '/admin/addresses',
                            access: this.hasAccess('personal_view')
                        },
                    ]
                },
                {
                    icon: 'users',
                    title: 'Пользователи',
                    url: '/admin/users',
                    access: this.hasRole('admin'),
                    items: []
                },
                {
                    icon: 'terminal-2',
                    title: 'Логи',
                    url: '/admin/logs',
                    access: this.hasRole('admin'),
                    items: []
                },
            ]
        }
    },
    methods: {
        showItems(key) {
            if(!this.show) this.$emit('open');
            this.showItem = key;

            const height = this.$refs[`item-${key}`][0].scrollHeight;

            if(height === this.heightItem) {
                this.heightItem = 0;
                this.$emit('update:navHeight', 0);
            } else {
                this.heightItem = height;
                this.$emit('update:navHeight', height);
            }
        }
    },
    watch: {
        show: {
            handler(val) {
                if(!val) {
                    this.showItem = null
                    this.heightItem = 0;
                    this.$emit('update:navHeight', 0);
                }
            },
            deep: true
        }
    }
}
</script>
