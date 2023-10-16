<template>
    <div class="flex items-center gap-2">
        <div class="relative group flex justify-center">
            <a href="/" class="block p-1 hover:bg-indigo-400 hover:text-white rounded-full transition dark:text-white">
                <Icon name="world" size="5"/>
            </a>
            <Tooltip title="Главная"/>
        </div>
        <template v-for="link in links" :key="link">
            <div v-if="link.access" class="relative group flex justify-center">
                <Link
                    :href="link.url"
                    class="block p-1 hover:bg-indigo-400 hover:text-white rounded-full transition dark:text-white"
                    :class="{ '!bg-indigo-400 !text-white': $page.url.startsWith(link.url) }"
                >
                    <Icon :name="link.icon" size="5"/>
                </Link>
                <Tooltip v-if="link.title" :title="link.title"/>
            </div>
        </template>
        <div class="relative group flex justify-center">
            <a @click="toggleTheme()" class="block p-1 hover:bg-indigo-400 hover:text-white rounded-full transition cursor-pointer dark:text-white">
                <Icon name="moon" size="5"/>
            </a>
            <Tooltip title="Темная тема"/>
        </div>
        <div v-if="$page.props.auth.user" class="relative group flex justify-center">
            <a href="/admin/auth/logout" class="block p-1 hover:bg-indigo-400 hover:text-white rounded-full transition cursor-pointer dark:text-white">
                <Icon name="logout" size="5"/>
            </a>
            <Tooltip title="Выход"/>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            darkMode: localStorage.getItem('darkMode') === 'true',
            links: [
                {
                    icon: 'user-circle',
                    title: 'Профиль',
                    url: '/admin/profile',
                    access: true
                },
                {
                    icon: 'folder',
                    title: 'Медиафайлы',
                    url: '/admin/media',
                    access: this.hasAccess('media_view')
                },
                {
                    icon: 'settings',
                    title: 'Настройки',
                    url: '/admin/settings',
                    access: this.hasRole('admin')
                },
            ]
        }
    },
    methods: {
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);

            if(this.darkMode) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        }
    }
}
</script>