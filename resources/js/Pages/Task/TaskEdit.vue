<script setup>
import ModalComponent from '@/Components/ModalComponent.vue';
import {useForm} from '@inertiajs/vue3';
import {watch} from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: true,
    },
    task: {
        type: Object,
    },
    users: {
        type: Array,
        required: true,
    },
});

// Initialize form with useForm
const form = useForm({
    title: '',
    description: '',
    status: 0,
    user_id: null,
});

// Watch for changes in props.task and update the form
watch(
    () => props.task,
    (newTask) => {
        if (newTask) {
            form.title = newTask.title || '';
            form.description = newTask.description || '';
            form.status = newTask.status || 0;
            form.user_id = newTask.user_id || null;
        }
    },
    {immediate: true}
);

// Form submission
const submitForm = () => {
    form.put(route('tasks.update', props.task.id));
    closeModal();
};

const emit = defineEmits(['update:modelValue']);

const closeModal = () => {
    emit('update:modelValue', false);

    form.reset();
}
</script>

<template>
    <ModalComponent v-model="props.modelValue">
        <div class="mt-2">
            <form @submit.prevent="submitForm">
                <div class="space-y-12">

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">Task Edit</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">Task details will be updated on save.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-5">
                            <!-- Task Title -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="title">Title</label>
                                <div class="mt-2">
                                    <input id="title" v-model="form.title" autocomplete="title"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                           name="title"
                                           type="text"/>
                                </div>
                            </div>

                            <!-- Task Description -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900"
                                       for="description">Description</label>
                                <div class="mt-2">
                                    <textarea id="description" v-model="form.description"
                                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                              name="description"/>
                                </div>
                            </div>

                            <!-- Task Status -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="status">Status</label>
                                <div class="mt-2">
                                    <select id="status" v-model="form.status"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                            name="status">
                                        <option :value="0">Pending</option>
                                        <option :value="1">In Progress</option>
                                        <option :value="2">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 flex gap-4 justify-end">
            <button
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                type="button" @click="closeModal">
                Close
            </button>
            <button
                :disabled="form.processing"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                @click="submitForm">
                Save changes
            </button>
        </div>
    </ModalComponent>
</template>
