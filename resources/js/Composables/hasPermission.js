import { usePage } from "@inertiajs/vue3";

export const hasPermission = (name) => {
    return usePage().props.auth.user.permissions.includes(name);
};
