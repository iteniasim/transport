import { usePage } from "@inertiajs/vue3";

export const isAuthenticated = () => {
    const { auth } = usePage().props;
    return auth.user !== null && auth.user !== undefined;
};

export const hasPermission = (name) => {
    if (!isAuthenticated()) return false;

    const { permissions, roles } = usePage().props.auth.user;
    return (
        permissions.some(p => p.name === name) ||
        roles.some(role => role.permissions.some(p => p.name === name))
    );
};

export const hasRole = (name) => {
    if (!isAuthenticated()) return false;

    const { roles } = usePage().props.auth.user;
    return roles.some(role => role.name === name);
};
