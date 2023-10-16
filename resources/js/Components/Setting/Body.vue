<template>
    <PageBlock>
        <PageTitle>Настройки</PageTitle>
        <Alert/>
        <SettingSectionList :sections="sections"/>
        <slot/>
    </PageBlock>
</template>

<script>
export default {
    props: {
        sections: [Object, Array]
    }
}
</script>