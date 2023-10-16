<template>
    <PageBlock>
        <Alert/>
        <div v-if="$page.props.auth.user && !$page.props.auth.user.roles.length" class="flex flex-col items-center gap-6">
            <img width="400" src="/images/access-denied.gif" alt="access-denied">
            <div class="text-center">
                <div class="font-bold text-xl text-slate-700 mb-2">Доступ запрещен</div>
                <div class="text-sm">Обратитесь к администратору для решения проблемы.</div>
            </div>
        </div>
    </PageBlock>
</template>

<script>
export default {
    methods: {

    }
}
</script>