<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import TaskEdit from './TaskEdit.vue';
import TaskTable from './TaskTable.vue';

const props = defineProps({
    tasks: Object,
    users: Array, // For assigning users to tasks
});

const pageTitle = 'Tasks';

// State for selected task and modal visibility
const selectedTask = ref(null);
const isTaskEdit = ref(false);

// Update selectedTask and open modal
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

    <AppLayout>
        <div class="flex flex-col gap-4">
            <!-- Table of tasks -->
            <TaskTable :tasks="props.tasks.data" @editTask="setEditTask" />

            <!-- Pagination for tasks -->
            <Pagination :links="props.tasks.links" />
        </div>

        <!-- Modal for editing/creating tasks -->
        <TaskEdit v-model="isTaskEdit" :task="selectedTask" :users="props.users" title="Edit Task" />
    </AppLayout>
</template>
