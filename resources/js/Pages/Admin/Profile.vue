<template>
    <Head title="Профиль"/>
    <ModalConfirmPassword ref="modalConfirmPassword"/>
    <PageBlock>
        <PageTitle>Мой профиль</PageTitle>
        <Alert/>
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div>
                <PageTitle level="2">Основные данные</PageTitle>
                <div class="space-y-4">
                    <FormTextInput v-model="$page.props.auth.user.surname" label="Фамилия" disabled/>
                    <FormTextInput v-model="$page.props.auth.user.name" label="Имя" disabled/>
                    <FormTextInput v-model="$page.props.auth.user.patronymic" label="Отчество" disabled/>
                    <FormTextInput v-model="$page.props.auth.user.email" label="Email" disabled/>
                </div>
            </div>
            <div>
                <PageTitle level="2">Сеансы браузера</PageTitle>
                <PageListBody class="mb-4 max-h-[284px]" w="full">
                    <PageListItem v-for="session in sessions" :key="session" class="!min-w-fit">
                        <div class="flex items-center gap-4">
                            <Icon v-if="session.agent.is_desktop" name="device-desktop" class="flex-none"/>
                            <Icon v-else name="device-mobile" class="flex-none"/>
                            <div class="space-y-1">
                                <div class="font-bold">
                                    {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                                    <span class="text-xs font-light">({{ session.device }})</span>
                                </div>
                                <div class="text-xs">
                                    {{ session.ip_address }},
                                    <span v-if="session.is_current_device" class="text-green-600 font-medium">Это устройство</span>
                                    <span v-else>Последняя активность {{ session.last_active }}</span>
                                </div>
                            </div>
                        </div>
                    </PageListItem>
                </PageListBody>
                <MainBtn @click="closeSessions()">Выйти из всех сеансов</MainBtn>
            </div>
        </div>
    </PageBlock>
</template>

<script>
export default {
    props: {
        sessions: Object
    },
    methods: {
        async closeSessions(id) {
            const password = await this.$refs.modalConfirmPassword.show('Вы действительно хотите завершить все сеансы кроме текущего?')

            if(password) {
                this.$inertia.post(`/admin/profile/sessions`, { password: password}, {
                    onError:() => {
                        console.log('error')
                }
                });
            }
        },
    }
}
</script>