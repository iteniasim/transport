<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import RoleEdit from '@/pages/roles/RoleEdit.vue';
import RoleGrid from '@/pages/roles/RoleGrid.vue';
import type { BreadcrumbItem, Permission, Role } from '@/types/index.js';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    roles: Array<Role>,
    permissions: Array<Permission>,
});

const pageTitle = 'Roles';

// State for selected role and modal visibility
const selectedRole = ref(null);
const isRoleEdit = ref(false);

// Update selectedRole and open role edit modal
const setEditRole = (role:any) => {
    selectedRole.value = role;
    isRoleEdit.value = true;
};

// Reset selectedRole when modal is closed
watch(isRoleEdit, (newVal) => {
    if (!newVal) {
        selectedRole.value = null;
    }
});
</script>

<template>
    <Head :title="pageTitle" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4">
            <RoleGrid :roles="props.roles" @editRole="setEditRole"/>
        </div>


        <RoleEdit v-if="selectedRole" v-model="isRoleEdit" :role="selectedRole" :permissions="props.permissions"/>
    </AppLayout>
</template>
