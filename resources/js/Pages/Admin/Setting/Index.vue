<template>
    <Head title="Настройки"/>
    <SettingBody :sections="sections"/>
</template>

<script>
export default {
    props: {
        sections: [Object, Array]
    }
}
</script>