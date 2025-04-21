<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">roles</h1>
                <p v-if="roles.length" class="mt-2 text-sm text-gray-700">A list of all the roles.</p>
                <p v-else class="mt-2 text-sm text-gray-700">No roles found.</p>
            </div>
        </div>

        <div v-if="roles.length" class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <div class="grid grid-cols-4 gap-4">
                            <div v-for="role in roles" :key="`role-${role.id}`"
                                class="max-w-sm rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <RoleItem :role="role" @editRole="emit('edit-role', role)"  />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import RoleItem from '@/pages/roles/RoleItem.vue';
import { Role } from "@/types";

defineProps({
    roles: {
        type: Array<Role>,
        required: true,
    },
});

const emit = defineEmits(['edit-role']);
</script>
