<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserActions from '@/Pages/User/Actions.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({
    users: Object,
});
const pageTitle = 'Users';
</script>


<template>

    <Head :title="pageTitle" />

    <AppLayout>
        <div class="flex flex-col gap-4">
            <div class="w-full overflow-x-auto border border-base-content/25 rounded-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(user, index) in props.users.data" :key="`user-${user.id}`" class="hover">
                            <th>{{ index + 1 }}</th>
                            <td class="text-nowrap">{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span v-for="roleName in user.roles.map((role) => role.name)" :key="roleName + '-' + user.id"
                                    class="badge badge-soft text-xs">
                                    {{ roleName }}
                                </span>
                            </td>
                            <td>
                                <UserActions :user="user" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="props.users.links" />
        </div>
    </AppLayout>
</template>
