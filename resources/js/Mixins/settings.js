export const settings = {
    methods: {
        getSetting(items = []) {
            const result = [];

            items.forEach(setting => {
                result[setting.name] = setting.value;
            });

            return result;
        }
    }
}