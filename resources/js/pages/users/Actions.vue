<template>
    <div class="flex gap-4">
        <button v-if="hasPermission('update_users')" type="button" class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                aria-label="Edit User" @click="editAction(props.user)">
            <Pencil />
        </button>
        <button class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                v-if="!props.user['deleted_at'] && hasPermission('delete_users')" aria-label="Delete User" @click="deleteAction(props.user)">
            <Trash2 />
        </button>
        <button v-else-if="props.user['deleted_at'] && hasPermission('restore_users')" class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                aria-label="Restore User" @click="restoreAction(props.user)">
            <RotateCcw />
        </button>
    </div>
</template>

<script setup lang="ts">
import { hasPermission } from '@/../composables/hasPermission.js';
import { router } from "@inertiajs/vue3";
import { Pencil, RotateCcw, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['editUser']);

const editAction = (user) => {
    emit('editUser', user);
}

const deleteAction = (user) => {
    if (confirm("Are you sure you want to delete " + user.name)) {
        router.delete(route("users.destroy", user.id), {
            preserveScroll: true,
        });
    }
};

const restoreAction = (user) => {
    if (confirm("Are you sure you want to restore " + user.name)) {
        router.post(route("users.restore", user.id), {}, {
            preserveScroll: true,
        });
    }
};
</script>
