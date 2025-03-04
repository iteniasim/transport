<script setup lang="ts">
import ModalComponent from '@/components/ModalComponent.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

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
    name: '',
    load_type: '',
    from: '',
    to: '',
    weight: '',
    cost: '',
    status: 0,
});

// Watch for changes in props.task and update the form
watch(
    () => props.task,
    (newTask) => {
        if (newTask) {
            form.name = newTask.name || '';
            form.load_type = newTask.load_type || '';
            form.from = newTask.from || '';
            form.to = newTask.to || '';
            form.weight = newTask.weight || '';
            form.cost = newTask.cost || '';
            form.status = newTask.status || 0;
        }
    },
    { immediate: true }
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

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});
</script>

<template>
    <ModalComponent v-model="isOpen">
        <div class="mt-2">
            <form @submit.prevent="submitForm">
                <div class="space-y-12">

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">Task Edit</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">Task details will be updated on save.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-5">
                            <!-- Task Name -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="name">Name</label>
                                <div class="mt-2">
                                    <input id="name" v-model="form.name" autocomplete="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="name" type="text" />
                                </div>
                            </div>

                            <!-- Task Load Type -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="load_type">Load Type</label>
                                <div>
                                    <input id="load_type" v-model="form.load_type" autocomplete="load_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="load_type" type="text" />
                                </div>
                            </div>

                            <!-- Task From -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="from">From</label>
                                <div>
                                    <input id="from" v-model="form.from" autocomplete="from"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="from" type="text" />
                                </div>
                            </div>

                            <!-- Task To -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="to">To</label>
                                <div>
                                    <input id="to" v-model="form.to" autocomplete="to"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="to" type="text" />
                                </div>
                            </div>


                            <!-- Task Weight -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="weight">Weight</label>
                                <div>
                                    <input id="weight" v-model="form.weight" autocomplete="weight"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="weight" type="text" />
                                </div>
                            </div>


                            <!-- Task Cost -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="cost">Cost</label>
                                <div>
                                    <input id="cost" v-model="form.cost" autocomplete="cost"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="cost" type="text" />
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
            <button :disabled="form.processing"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                @click="submitForm">
                Save changes
            </button>
        </div>
    </ModalComponent>
</template>
