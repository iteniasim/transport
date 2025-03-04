import {usePage} from "@inertiajs/vue3";

export const hasPermission = (name) => {
    const {permissions, roles} = usePage().props.auth.user;
    return permissions.some(p => p.name === name) || roles.some(role => role.permissions.some(p => p.name === name));
};
