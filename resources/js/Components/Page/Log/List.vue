<template>
    <ModalConfirm ref="modalConfirm"/>
    <div class="w-full overflow-x-auto overflow-x-auto bg-gray-50 border-2 border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500">
        <div class="min-w-[1720px] flex items-center justify-between gap-4 p-4 border-b-2 border-gray-300">
            <div class="flex items-center gap-4 w-full">
                <div class="w-[24px]"></div>
                <div class="grid grid-cols-10 gap-4 font-bold w-full text-center dark:text-white">
                    <div>Время</div>
                    <div>Действие</div>
                    <div>Метод</div>
                    <div>Область</div>
                    <div>Объект</div>
                    <div>Примечание</div>
                    <div>Инициатор</div>
                    <div>Агент</div>
                    <div>URL</div>
                    <div>IP</div>
                </div>
            </div>
        </div>
        <template v-if="logs.length">
            <PageListItem v-for="log in logs" :key="log" class="min-w-[1720px]">
                <div class="flex items-center gap-4 w-full dark:text-white">
                    <Icon name="note" size="5" class="flex-none"/>
                    <div class="grid grid-cols-10 gap-4 w-full text-center text-xs break-words">
                        <div>{{ new Date(log.created_at).toLocaleString() }}</div>
                        <div>{{ log.action }}</div>
                        <div>{{ log.method }}</div>
                        <div>{{ log.component }}</div>
                        <div v-html="getObject(log.action, log.component, log.object_id)"></div>
                        <div @click="showText" class="truncate cursor-pointer">{{ log.note ?? '-' }}</div>
                        <div v-html="getTarget(log.target, log.target_id)"></div>
                        <div @click="showText" class="truncate cursor-pointer">{{ log.agent }}</div>
                        <div>{{ log.url }}</div>
                        <div>{{ log.ip }}</div>
                    </div>
                </div>
            </PageListItem>
        </template>
        <PageListItem v-else :empty="true">
            <Icon name="terminal-2"/>
            Логи не найдены
        </PageListItem>
    </div>
</template>

<script>
export default {
    props: {
        logs: Object
    },
    methods: {
        showText(event) {
            event.target.classList.remove('truncate', 'cursor-pointer');
        },
        getObject(action, component, object_id) {
            if(action !== 'force_delete') {
                if(component === 'news') return `<a href="/admin/news/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Новость (ID: ${object_id})</a>`;
                if(component === 'article') return `<a href="/admin/articles/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Страница (ID: ${object_id})</a>`;
                if(component === 'category') return `<div class="w-fit mx-auto font-bold text-gray-600 cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Категория (ID: ${object_id})</div>`;
                if(component === 'permission') return `<div class="w-fit mx-auto font-bold text-gray-600 cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Право</div>`;
                if(component === 'role') return `<div class="w-fit mx-auto font-bold text-gray-600 cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Роль</div>`;
                if(component === 'right') return `<div class="w-fit mx-auto font-bold text-gray-600 cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Индивидуальное право</div>`;
                if(component === 'event') return `<a href="/admin/events/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Мероприятие (ID: ${object_id})</a>`;
                if(component === 'tag') return `<a href="/admin/tags/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Тег (ID: ${object_id})</a>`;
                if(component === 'department') return `<a href="/admin/departments/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Департамент (ID: ${object_id})</a>`;
                if(component === 'department-type') return `<a href="/admin/department-types/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Тип департамента (ID: ${object_id})</a>`;
                if(component === 'employee') return `<a href="/admin/employees/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Сотрудник (ID: ${object_id})</a>`;
                if(component === 'address') return `<a href="/admin/addresses/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Адрес (ID: ${object_id})</a>`;
                if(component === 'user') return `<a href="/admin/users/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Пользователь (ID: ${object_id})</a>`;
                if(component === 'comment') return `<a href="/admin/comments/${object_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Комментарий (ID: ${object_id})</a>`;
            }

            return object_id;
        },
        getTarget(target, target_id) {
            if(target === 'user') return `<a href="/admin/users/${target_id}/edit" target="_blank" class="w-fit mx-auto font-bold text-gray-600 hover:text-blue-600 transition cursor-pointer dark:text-slate-400 dark:hover:text-blue-400">Пользователь (ID: ${target_id})</a>`;
        }
    }
}
</script>