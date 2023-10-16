<template>
    <Head title="Создание сотрудника"/>
    <PageBlock>
        <PageTitle>Создание сотрудника</PageTitle>
        <Alert/>

        <PageGroup class="mb-4">
            <FormTextInput v-model="form.surname" v-model:error="form.errors.surname" label="Фамилия" placeholder="Введите фамилию" id="surname" required/>
            <FormTextInput v-model="form.name" v-model:error="form.errors.name" label="Имя" placeholder="Введите имя" id="name" required/>
            <FormTextInput v-model="form.patronymic" v-model:error="form.errors.patronymic" label="Отчество" placeholder="Введите отчество" id="patronymic"/>
            <FormTextInput v-model="form.level_education" v-model:error="form.errors.level_education" label="Уровень образования" placeholder="Введите уровень образования" id="level_education"/>
            <FormTextInput v-model="form.general_experience" v-model:error="form.errors.general_experience" label="Общий стаж" placeholder="Введите общий стаж" type="number" min="0" id="general_experience"/>
            <FormTextInput v-model="form.scientific_experience" v-model:error="form.errors.scientific_experience" label="Научный стаж" placeholder="Введите научный стаж" type="number" min="0" id="scientific_experience"/>
        </PageGroup>

        <FormMediaInput v-model="form.photo_id" v-model:error="form.errors.photo_id" label="Фотография" type="image" mode="single" preview="true"/>

        <PageTitle level="2">
            Должности
            <IconBtn @click="addPosition" name="plus" size="5" variation="success" title="Добавить"/>
        </PageTitle>

        <div v-if="form.positions.length" class="border-2 border-gray-300 rounded-lg mb-4">
            <div
                v-for="(position, index) in form.positions"
                :key="index"
                class="flex items-center p-4 gap-4 border-b border-gray-300 last:border-b-0 hover:bg-slate-200 transition"
            >
                <IconBtn @click="removePosition(index)" class="flex-none" name="x" size="5" variation="empty" title="Удалить"/>
                <PageGroup class="w-full">
                    <FormSelectInput v-model="position.department_id" v-model:error="form.errors[`positions.${index}.department_id`]" label="Подразделение" :id="`${index}-department_id`">
                        <option :value="null">Не выбрано</option>
                        <option
                            v-for="department in departments"
                            :key="departments"
                            :value="department.id"
                        >
                            {{ department.title }}
                        </option>
                    </FormSelectInput>
                    <FormSelectInput v-model="position.address_id" v-model:error="form.errors[`positions.${index}.address_id`]" label="Адрес" desc="При наличии" :id="`${index}-address_id`">
                        <option :value="null">Не выбрано</option>
                        <option
                            v-for="address in addresses"
                            :key="address"
                            :value="address.id"
                        >
                            {{ getAddress(address) }}
                        </option>
                    </FormSelectInput>
                    <FormTextInput v-model="position.subdivision" v-model:error="form.errors[`positions.${index}.subdivision`]" label="Подотдел" placeholder="Введите подотдел" :id="`${index}-subdivision`"/>
                    <FormTextInput v-model="position.cabinet" v-model:error="form.errors[`positions.${index}.cabinet`]" label="Номер кабинета" placeholder="Введите номер кабинета" :id="`${index}-cabinet`"/>
                    <FormTextInput v-model="position.title" v-model:error="form.errors[`positions.${index}.title`]" label="Должность" placeholder="Введите должность" :id="`${index}-title`"/>
                    <FormTextInput v-model="position.experience" v-model:error="form.errors[`positions.${index}.experience`]" label="Стаж" placeholder="Введите стаж" type="number" :id="`${index}-experience`"/>
                    <FormSelectInput v-model="position.status" v-model:error="form.errors[`positions.${index}.status`]" label="Статус" :id="`${index}-status`">
                        <option value="0">Уволен</option>
                        <option value="1">Работает</option>
                    </FormSelectInput>
                </PageGroup>
            </div>
        </div>

        <PageTitle level="2">
            Атрибуты
            <IconBtn @click="addAttribute" name="plus" size="5" variation="success" title="Добавить"/>
        </PageTitle>

        <div v-if="form.attributes.length" class="border-2 border-gray-300 rounded-lg mb-4">
            <div
                v-for="(attribute, index) in form.attributes"
                :key="index"
                class="flex items-center p-4 gap-4 border-b border-gray-300 last:border-b-0 hover:bg-slate-200 transition"
            >
                <IconBtn @click="removeAttribute(index)" class="flex-none" name="x" size="5" variation="empty" title="Удалить"/>
                <PageGroup class="w-full">
                    <FormTextInput v-model="attribute.title" v-model:error="form.errors[`attributes.${index}.title`]" label="Название" placeholder="Введите название" :id="`${index}-attribute-title`"/>
                    <FormTextareaInput v-model="attribute.value" v-model:error="form.errors[`attributes.${index}.value`]" label="Значение" placeholder="Введите значение" :id="`${index}-attribute-value`"/>
                </PageGroup>
            </div>
        </div>

        <PageGroupBtn>
            <MainBtn to="/admin/employees" variation="second">Вернуться назад</MainBtn>
            <MainBtn @click="store" :loading="form.processing">Создать</MainBtn>
        </PageGroupBtn>
    </PageBlock>
</template>

<script>
export default {
    props: {
        departments: Object,
        addresses: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                surname: null,
                name: null,
                patronymic: null,
                level_education: null,
                general_experience: 0,
                scientific_experience: 0,
                photo_id: null,
                positions: [],
                attributes: []
            })
        }
    },
    methods: {
        addPosition() {
            this.form.positions.push({
                department_id: null,
                address_id: null,
                subdivision: null,
                cabinet: null,
                title: null,
                experience: 0,
                status: 1
            });
        },
        addAttribute() {
            this.form.attributes.push({
                title: null,
                value: null
            });
        },
        removePosition(index) {
            this.form.positions.splice(index, 1);
        },
        removeAttribute(index) {
            this.form.attributes.splice(index, 1);
        },
        getAttributesJSON(attributes) {
            let result = attributes;

            result.forEach(el => {
               el.value = JSON.stringify(el.value);
            });

            return result;
        },
        store() {
            this.form.transform((data) => ({
                ...data,
                attributes: this.getAttributesJSON(this.form.attributes),
            }))
                .post('/admin/employees');
        }
    }
}
</script>