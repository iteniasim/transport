<template>
    <div class="flex gap-4">
        <template v-if="!isTaskDeleted">
            <!-- Request Task Action -->
            <button type="button" class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                    aria-label="Request Task" @click="requestTask(props.task)">
                <UserPlusIcon class="size-4" />
            </button>

            <!-- Edit Action -->
            <button type="button" class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                    aria-label="Edit Task" @click="editAction(props.task)">
                <PencilSquareIcon class="size-4" />
            </button>

            <!-- Delete Action -->
            <button class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                    aria-label="Delete Task" @click="deleteAction(props.task)">
                <TrashIcon class="size-4" />
            </button>
        </template>

        <!-- Restore Action -->
        <button v-else class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
                aria-label="Restore Task" @click="restoreAction(props.task)">
            <ArrowPathIcon class="size-4" />
        </button>
    </div>
</template>

<script setup>
import {ArrowPathIcon, PencilSquareIcon, TrashIcon, UserPlusIcon} from '@heroicons/vue/24/solid';

import {router} from "@inertiajs/vue3";
import {computed} from 'vue'

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['edit-task']);

const isTaskDeleted = computed(() => {
    return !!props.task['deleted_at'];
})

const editAction = (task) => {
    emit('editTask', task);
}

const requestTask = (task) => {
    if (confirm("Are you sure you want to request task: " + task.title)) {
        router.post(route("tasks.request", task.id), {}, {
            preserveScroll: true,
        });
    }
}

const deleteAction = (task) => {
    if (confirm("Are you sure you want to delete task: " + task.title)) {
        router.delete(route("tasks.destroy", task.id), {
            preserveScroll: true,
        });
    }
};

const restoreAction = (task) => {
    if (confirm("Are you sure you want to restore task: " + task.title)) {
        router.post(route("tasks.restore", task.id), {}, {
            preserveScroll: true,
        });
    }
};
</script>
