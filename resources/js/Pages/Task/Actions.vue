<template>
    <div class="flex gap-4">
        <!-- Edit Action -->
        <button type="button" class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
            @click="editAction(props.task)">
            <PencilSquareIcon class="size-4" />
        </button>

        <!-- Delete Action -->
        <button class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
            v-if="!props.task['deleted_at']" @click="deleteAction(props.task)">
            <TrashIcon class="size-4" />
        </button>

        <!-- Restore Action -->
        <button v-else class="bg-red-50 hover:bg-red-200 text-red-500 font-bold py-2 px-2 rounded-full inline-flex items-center"
            @click="restoreAction(props.task)">
            <ArrowPathIcon class="size-4" />
        </button>
    </div>
</template>

<script setup>
import { ArrowPathIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import { router } from "@inertiajs/vue3";

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['editTask']);

const editAction = (task) => {
    emit('editTask', task);
}

const deleteAction = (task) => {
    if (confirm("Are you sure you want to delete task: " + task.title)) {
        router.delete(route("tasks.destroy", task.id));
    }
};

const restoreAction = (task) => {
    if (confirm("Are you sure you want to restore task: " + task.title)) {
        router.post(route("tasks.restore", task.id));
    }
};
</script>
