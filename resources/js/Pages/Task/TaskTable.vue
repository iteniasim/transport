<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Tasks</h1>
                <p v-if="tasks.length" class="mt-2 text-sm text-gray-700">A list of all the tasks.</p>
                <p v-else class="mt-2 text-sm text-gray-700">No tasks found.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button type="button" @click="setCreateTask"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add task
                </button>
            </div>
        </div>
        <div v-if="tasks.length" class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6" scope="col">Name</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">Load Type</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">From</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">To</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">Weight</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">Cost</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" scope="col">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Assigned User</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(task) in tasks" :key="`task-${task.id}`">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ task.name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.load_type }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.from }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.to }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.weight }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.cost }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <StatusBadge :color="getTaskStatusColor(task.status)" :content="getTaskStatusLabel(task.status)"/>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ task.user?.name || '-' }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <TaskActions :task="task" @editTask="setEditTask" />
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

<script setup>
import TaskActions from '@/Pages/Task/Actions.vue'; // Update this path if needed
import {ref} from 'vue';
import {useMappings} from "@/Composables/useMappings.js";
import StatusBadge from "@/Components/StatusBadge.vue";

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['create-task', 'edit-task']);

const {getTaskStatusLabel, getTaskStatusColor} = useMappings()
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
</script>
