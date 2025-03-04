<template>
    <div class="flex justify-end">
        <Actions :task="task" @editTask="setEditTask" @openAssignUserModal="openAssignUserModal" />
    </div>
    <div class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
        {{ task.name }}
        <StatusBadge :color="getTaskStatusColor(task.status)" :content="getTaskStatusLabel(task.status)" />
    </div>
    <div v-if="task.requested_users_count && !task.user" class="mb-2 font-semibold text-gray-900 dark:text-white">
        {{ driverText(task.requested_users_count) }} available.
    </div>
    <div v-else class="px-3 text-sm text-gray-500">
        <span class="font-semibold">Assigned User: </span>
        <span>{{ task.user?.name || '-' }}</span>
    </div>
    <div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">Task Owner: </span>
            <span class="italic">{{ task.creator.name }}</span>
        </div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">Load Type: </span>
            <span>{{ task.load_type }}</span>
        </div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">From: </span>
            <span>{{ task.from }}</span>
        </div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">To: </span>
            <span>{{ task.to }}</span>
        </div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">Weight: </span>
            <span>{{ task.weight }}</span>
        </div>
        <div class="px-3 text-sm text-gray-500">
            <span class="font-semibold">Cost: </span>
            <span>{{ task.cost }}</span>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useMappings } from '@/../composables/useMappings.js';
const { getTaskStatusLabel, getTaskStatusColor } = useMappings();
import Actions from '@/pages/tasks/Actions.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { ref } from 'vue';

defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['create-task', 'edit-task', 'assign-users']);

// Define the selectedTask ref to store the selected task
const selectedTask = ref(null);

// Update the selectedTask when the event is emitted
const setEditTask = (task) => {
    selectedTask.value = task;
    emit('edit-task', task);
};

const openAssignUserModal = (task) => {
    selectedTask.value = task;
    emit('assign-users', task);
};

const driverText = (count) => {
    const driverWord = count === 1 ? 'driver' : 'drivers';
    return `${count} ${driverWord}`;
};
</script>
