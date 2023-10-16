import { usePage } from '@inertiajs/vue3';

export const permissions = {
    methods: {
        getRights: (name) => usePage().props.auth.user ? usePage().props.auth.user[`${name}_rights`] : [],
        hasRole: (name) => usePage().props.auth.user && usePage().props.auth.user.roles.includes(name),
        hasPermission: (name) => usePage().props.auth.user && usePage().props.auth.user.permissions.includes(name),
        hasAccess: function (name) {
            return usePage().props.auth.user && (this.hasRole('admin') || (this.hasRole('user') && this.hasPermission(name)))
        },
        hasAccessModel: function (name, id) {
            return usePage().props.auth.user && (this.hasRole('admin') || (
                this.hasRole('user') && this.hasPermission(`${name}_edit`) && (!this.getRights(name).length || this.getRights(name).includes(id))
            ))
        }
    }
}