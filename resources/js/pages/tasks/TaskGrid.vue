<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Tasks</h1>
                <p v-if="tasks.length" class="mt-2 text-sm text-gray-700">A list of all the tasks.</p>
                <p v-else class="mt-2 text-sm text-gray-700">No tasks found.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    type="button" @click="setCreateTask">
                    Add task
                </button>
            </div>
        </div>
        <div v-if="tasks.length" class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <div class="grid grid-cols-4 gap-4">
                            <div v-for="(task) in tasks" :key="`task-${task.id}`"
                                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
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
                                    <span class="font-semibold">Assigned User:</span>
                                    <span>{{ task.user?.name || '-' }}</span>
                                </div>
                                <div>
                                    <div class="px-3 text-sm text-gray-500">
                                        <span class="font-semibold">Load Type:</span>
                                        <span>{{ task.load_type }}</span>
                                    </div>
                                    <div class="px-3 text-sm text-gray-500">
                                        <span class="font-semibold">From:</span>
                                        <span>{{ task.from }}</span>
                                    </div>
                                    <div class="px-3 text-sm text-gray-500">
                                        <span class="font-semibold">To:</span>
                                        <span>{{ task.to }}</span>
                                    </div>
                                    <div class="px-3 text-sm text-gray-500">
                                        <span class="font-semibold">Weight:</span>
                                        <span>{{ task.weight }}</span>
                                    </div>
                                    <div class="px-3 text-sm text-gray-500">
                                        <span class="font-semibold">Cost:</span>
                                        <span>{{ task.cost }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import StatusBadge from "@/components/StatusBadge.vue";
import { useMappings } from "@/../composables/useMappings.js";
import { ref } from 'vue';
import Actions from '@/pages/tasks/Actions.vue';

defineProps({
    tasks: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['create-task', 'edit-task', 'assign-users']);

const { getTaskStatusLabel, getTaskStatusColor } = useMappings()
// Define the selectedTask ref to store the selected task
const selectedTask = ref(null);

// Open create task model when the event is emitted
const setCreateTask = () => {
    emit('create-task')
};

// Update the selectedTask when the event is emitted
const setEditTask = (task) => {
    selectedTask.value = task;
    emit('edit-task', task)
};

const openAssignUserModal = (task) => {
    selectedTask.value = task;
    emit('assign-users', task)
};


const driverText = (count) => {
    const driverWord = count === 1 ? 'driver' : 'drivers';
    return `${count} ${driverWord}`;
};
</script>
