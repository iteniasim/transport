<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {ref, watch} from 'vue';
import type { BreadcrumbItem } from '@/types/index.js';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import TaskGrid from '@/pages/tasks/TaskGrid.vue';
import Pagination from '@/components/Pagination.vue';
import TaskCreate from '@/pages/tasks/TaskCreate.vue';
import TaskEdit from '@/pages/tasks/TaskEdit.vue';
import TaskAssignUsers from '@/pages/tasks/TaskAssignUsers.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    tasks: Object,
    users: Array, // For assigning users to tasks
});

const pageTitle = 'Tasks';

// State for selected task and modal visibility
const selectedTask = ref(null);
const isTaskCreate = ref(false);
const isTaskEdit = ref(false);
const isTaskAssign = ref(false);

const availableUsers = ref([]);

// Update selectedTask and open task create modal
const openCreateTaskModal = () => {
    isTaskCreate.value = true;
};

// Update selectedTask and open assign user modal
const openAssignUserModal = (task) => {
    selectedTask.value = task;

    // Fetch available users (you can replace this with your actual API call)
    try {
        axios.get(route('tasks.requested.users', task.id)).then(res => {
            availableUsers.value = res.data.requestedUsers;
        });
    } catch (error) {
        console.error("Failed to fetch users:", error);
    }
    isTaskAssign.value = true;
};
// Update selectedTask and open task edit modal
const setEditTask = (task) => {
    selectedTask.value = task;
    isTaskEdit.value = true;
};

// Reset selectedTask when modal is closed
watch(isTaskEdit, (newVal) => {
    if (!newVal) {
        selectedTask.value = null;
    }
});
</script>

<template>
    <Head :title="pageTitle" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4">
            <TaskGrid :tasks="props.tasks?.data" @assignUsers="openAssignUserModal" @createTask="openCreateTaskModal" @editTask="setEditTask"/>
            <Pagination v-if="props.tasks?.total" :links="props.tasks?.links"/>
        </div>

        <TaskCreate v-model="isTaskCreate" :users="props.users"/>
        <TaskEdit v-if="selectedTask" v-model="isTaskEdit" :task="selectedTask" :users="props.users"/>
        <TaskAssignUsers v-if="selectedTask" v-model="isTaskAssign" :task="selectedTask" :availableUsers="availableUsers"/>
    </AppLayout>
</template>
