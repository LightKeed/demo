<template>
    <Head title="Роли и права"/>
    <SettingBody :sections="sections">
        <PageRightsRole :roles="roles"/>
        <PageRightsPermission :filters="filters" :permissions="permissions" :groups="groups"/>
        <PageRightsIndividualRights/>
    </SettingBody>
</template>

<script>

export default {
    props: {
        filters: Object,
        sections: [Object, Array],
        roles: Object,
        permissions: Object,
        groups: Object
    }
}
</script>