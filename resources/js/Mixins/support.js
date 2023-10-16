export const support = {
    methods: {
        getFileSize(size) {
            if (size < 1024) return "0 KB";
            if (size < 1024 * 1024) return (size / 1024).toFixed(2) + " KB";
            if (size < 1024 * 1024 * 1024) return (size / (1024 * 1024)).toFixed(2) + " MB";
            return (size / (1024 * 1024 * 1024)).toFixed(2) + " GB";
        },
        getAddress(address) {
            return address ? [address.post_code, address.district, address.region, address.city, address.street, address.house_number, address.house_unit_number].filter(Boolean).join(", ") : 'Адрес не найден';
        }
    }
}