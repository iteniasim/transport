<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref, watch} from 'vue';
import TaskTable from "@/Pages/Task/TaskGrid.vue";
import TaskEdit from "@/Pages/Task/TaskEdit.vue";
import TaskCreate from "@/Pages/Task/TaskCreate.vue";

const props = defineProps({
    tasks: Object,
    users: Array, // For assigning users to tasks
});

const pageTitle = 'Tasks';

// State for selected task and modal visibility
const selectedTask = ref(null);
const isTaskCreate = ref(false);
const isTaskEdit = ref(false);

// Update selectedTask and open modal
const openCreateTaskModal = () => {
    isTaskCreate.value = true;
};
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
            <TaskTable :tasks="props.tasks.data" @createTask="openCreateTaskModal" @editTask="setEditTask"/>
            <Pagination v-if="props.tasks.total" :links="props.tasks.links"/>
        </div>

        <TaskCreate v-model="isTaskCreate" :users="props.users"/>
        <TaskEdit v-model="isTaskEdit" :task="selectedTask" :users="props.users"/>
    </AppLayout>
</template>
