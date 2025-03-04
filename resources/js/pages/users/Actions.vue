<template>
    <div class="flex gap-4">
        <button type="button" class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                aria-label="Edit User" @click="editAction(props.user)">
            <Pencil />
        </button>
        <button class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                v-if="!props.user['deleted_at']" aria-label="Delete User" @click="deleteAction(props.user)">
            <Archive />
        </button>
        <button v-else class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                aria-label="Restore User" @click="restoreAction(props.user)">
            <ArchiveRestore />
        </button>
    </div>
</template>

<script setup lang="ts">
import { Archive, Pencil, ArchiveRestore } from 'lucide-vue-next';
import {router} from "@inertiajs/vue3";

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
