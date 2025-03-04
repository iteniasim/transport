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
                    type="button"
                    @click="setCreateTask"
                >
                    Add task
                </button>
            </div>
        </div>
        <div v-if="tasks.length" class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <div class="grid grid-cols-4 gap-4">
                            <div
                                v-for="task in tasks"
                                :key="`task-${task.id}`"
                                class="max-w-sm rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                            >
                                <TaskItem :task="task" @editTask="setEditTask" @openAssignUserModal="openAssignUserModal" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import TaskItem from '@/pages/tasks/TaskItem.vue';
import Actions from '@/pages/tasks/Actions.vue';

defineProps({
    tasks: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['create-task', 'edit-task', 'assign-users']);


// Define the selectedTask ref to store the selected task
const selectedTask = ref(null);

// Open create task model
const setCreateTask = () => {
    emit('create-task');
};

// Update the selectedTask when the event is emitted
const setEditTask = (task) => {
    selectedTask.value = task;
    emit('edit-task', task);
};

const openAssignUserModal = (task) => {
    selectedTask.value = task;
    emit('assign-users', task);
};
</script>
