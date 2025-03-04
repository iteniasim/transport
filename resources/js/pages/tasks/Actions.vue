<template>
    <div class="flex gap-4">
        <!-- Other Actions -->
        <template v-if="!isTaskDeleted">
            <!-- Request Task Action -->
            <button v-if="hasPermission('request_tasks') && !isTaskAssigned && !isOwnTask" aria-label="Request Task"
                class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center" type="button"
                @click="requestTask(props.task)">
                <UserPlus />
            </button>

            <!-- Assign Task Action -->
            <button v-if="hasPermission('assign_tasks') && props.task.requested_users_count && isOwnTask" aria-label="Assign Task"
                class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center" type="button"
                @click="assignTask(props.task)">
                <UserCheck />
            </button>

            <!-- Edit Action -->
            <button v-if="hasPermission('update_tasks')" aria-label="Edit Task"
                class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center" type="button"
                @click="editAction(props.task)">
                <Pencil />
            </button>

            <!-- Delete Action -->
            <button v-if="hasPermission('delete_tasks')" aria-label="Delete Task"
                class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                @click="deleteAction(props.task)">
                <Trash2 />
            </button>
        </template>

        <!-- Restore Action -->
        <button v-else-if="hasPermission('restore_tasks')" aria-label="Restore Task"
            class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
            @click="restoreAction(props.task)">
            <RotateCcw />
        </button>
    </div>
</template>

<script setup lang="ts">
import { Trash2, Pencil, UserPlus, UserCheck, RotateCcw } from 'lucide-vue-next';
import { router, usePage } from "@inertiajs/vue3";
import { computed } from 'vue'
import { hasPermission } from '@/../composables/hasPermission.js';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['edit-task', 'assign-users',]);

const isTaskAssigned = computed(() => {
    return !!props.task['user_id'];
})

const isOwnTask = computed(() => {
    return props.task.creator.id == usePage().props.auth.user.id;
})

const isTaskDeleted = computed(() => {
    return !!props.task['deleted_at'];
})

const editAction = (task) => {
    emit('edit-task', task);
}

const assignTask = (task) => {
    emit('assign-users', task);
}

const requestTask = (task) => {
    if (confirm("Are you sure you want to request task: " + task.name)) {
        router.post(route("tasks.request", task.id), {}, {
            preserveScroll: true,
        });
    }
}

const deleteAction = (task) => {
    if (confirm("Are you sure you want to delete task: " + task.name)) {
        router.delete(route("tasks.destroy", task.id), {
            preserveScroll: true,
        });
    }
};

const restoreAction = (task) => {
    if (confirm("Are you sure you want to restore task: " + task.name)) {
        router.post(route("tasks.restore", task.id), {}, {
            preserveScroll: true,
        });
    }
};
</script>
