<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref, watch} from 'vue';
import UserTable from "@/Pages/User/UserTable.vue";
import UserEdit from "@/Pages/User/UserEdit.vue";

const props = defineProps({
    users: Object,
    roles: Array,
});

const pageTitle = 'Users';

// State for selected user and modal visibility
const selectedUser = ref(null);
const isUserEdit = ref(false);

// Update selectedUser and open modal
const setEditUser = (user) => {
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

    <AppLayout>
        <div class="flex flex-col gap-4">
            <UserTable :users="props.users.data" @editUser="setEditUser" />
            <Pagination v-if="props.users.total" :links="props.users.links"/>
        </div>

        <UserEdit v-model="isUserEdit" :roles="props.roles" :user="selectedUser"/>
    </AppLayout>
</template>
