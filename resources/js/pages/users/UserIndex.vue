<script lang="ts" setup>
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import UserEdit from '@/pages/users/UserEdit.vue';
import UserTable from '@/pages/users/UserTable.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

const pageTitle = 'Users';

const props = defineProps({
    users: {
        type: Object,
    },
    roles: {
        type: Array,
        default() {
            return [];
        }
    },
});

// State for selected user and modal visibility
const selectedUser = ref(null);
const isUserEdit = ref(false);

// Update selectedUser and open modal
const setEditUser = (user: any) => {
    selectedUser.value = user;
    isUserEdit.value = true;
};

// Reset selectedUser when modal is closed
watch(isUserEdit, (newVal) => {
    if (!newVal) {
        selectedUser.value = null;
    }
});
</script>

<template>
    <Head :title="pageTitle" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <UserTable :users="props.users?.data" @editUser="setEditUser" />
                <Pagination v-if="props.users?.total" :links="props.users.links" />
            </div>

            <UserEdit v-if="selectedUser" v-model="isUserEdit" :roles="props.roles" :user="selectedUser" />
        </div>
    </AppLayout>
</template>
