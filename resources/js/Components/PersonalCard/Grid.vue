<template>
    <div class="mb-4">
        <div v-if="cards.data && cards.data.length" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-2 max-h-[570px] overflow-y-auto">
            <div
                v-for="card in cards.data"
                :key="card"
                class="relative flex gap-2 border border-blue-600 rounded-xl cursor-pointer"
                @click="emit('select', card)"
            >
                <div v-if="selectedItems.find(item => item.id === card.id)" class="absolute top-2 right-2 bg-indigo-400 rounded-lg text-white p-1 z-[10] pointer-events-none select-none">
                    <Icon name="circle-check" size="5"/>
                </div>
                <div class="max-w-[150px] max-h-[150px] flex-none">
                    <img :src="`/media/images/${(card.employee.photo_id ?? 'default.webp')}`" alt="photo" class="h-[150px] object-cover rounded-lg">
                </div>
                <div class="p-2">
                    <div class="font-bold text-blue-600 mb-1">
                        {{ card.employee.surname }} {{ card.employee.name }} {{ card.employee.patronymic ?? '' }}
                    </div>
                    <div class="font-medium text-xs mb-2">{{ card.title }} {{ card.department.type ? card.department.type.title : '' }} {{ card.department.title ?? '' }}</div>
                    <div v-if="card.address ?? card.department.address" class="flex gap-1 mb-1">
                        <Icon name="map-pin" size="5" class="text-blue-600 flex-none"/>
                        {{ getAddress(card.address ?? card.department.address) }}
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex items-center justify-center gap-1 p-4 bg-gray-50 border-2 border-gray-300 rounded-lg">
            <Icon name="id-badge-2"/>
            Карточки сотрудников не найдены
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    cards: Object,
    selectedItems: [Object, Array]
})

const emit = defineEmits(['select']);
</script>