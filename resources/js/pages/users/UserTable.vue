<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
                <p v-if="users.length" class="mt-2 text-sm text-gray-700">A list of all the users in your account
                    including their name, title, email and role.</p>
                <p v-else class="mt-2 text-sm text-gray-700">No users found.</p>
            </div>
        </div>
        <div v-if="users.length" class="flow-root mt-8">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(user) in users" :key="`user-${user.id}`">
                                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-6">{{ user.name }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ user.email }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        <RoleBadges :user="user" />
                                    </td>
                                    <td class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap sm:pr-6">
                                        <Actions :user="user" @editUser="setEditUser" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup  lang="ts">
import RoleBadges from '@/components/RoleBadges.vue';
import { ref } from 'vue';
import Actions from '@/pages/users/Actions.vue';

defineProps({
    users: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['editUser']);

// Define the selectedUser ref to store the selected user
const selectedUser = ref(null);

// Update the selectedUser when the event is emitted
const setEditUser = (user) => {
    selectedUser.value = user;
    emit('editUser', user)
};
</script>
