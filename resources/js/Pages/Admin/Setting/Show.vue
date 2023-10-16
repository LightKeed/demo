<template>
    <Head :title="`Настройки (${section.title})`"/>
    <SettingBody :sections="sections">
        <SettingSectionBasic v-if="section.section === 'Basic'" :settings="getSetting(settings)"/>
        <SettingSectionGeneral v-else-if="section.section === 'General'" :settings="getSetting(settings)"/>
        <SettingSectionAdditional v-else-if="section.section === 'Additional'" :settings="getSetting(settings)"/>
        <SettingSectionServer v-else-if="section.section === 'Server'" :settings="getSetting(settings)"/>
        <SettingSectionHome v-if="section.section === 'Home'" :settings="getSetting(settings)"/>
    </SettingBody>
</template>

<script>
export default {
    props: {
        settings: Object,
        sections: [Object, Array],
        section: [Object, Array]
    }
}
</script>