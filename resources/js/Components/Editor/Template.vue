<template>
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        <div class="col-span-1">
            <div class="sticky top-2">
                <slot/>
            </div>
        </div>
        <div class="col-span-1 xl:col-span-4">
            <PageTitle>Структура</PageTitle>
            <EditorBody/>
        </div>
    </div>
</template>