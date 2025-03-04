<script setup lang="ts">
import ModalComponent from '@/components/ModalComponent.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: true,
    },
    task: {
        type: Object,
    },
    availableUsers: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    user_id: null,
});

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

// Form submission
const submitForm = () => {
    form.post(route('tasks.assign', props.task.id));
    closeModal();
};

const emit = defineEmits(['update:modelValue']);

const closeModal = () => {
    emit('update:modelValue', false);
    form.reset()
}
</script>

<template>
    <ModalComponent v-model="isOpen">
        <div class="mt-2">
            <form @submit.prevent="submitForm">
                <div class="space-y-12">

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">Task Assign</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">Assign the task to one of the available users.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-5">
                            <!-- Task Assigned To -->
                            <div class="sm:col-span-5">
                                <label class="block text-sm/6 font-medium text-gray-900" for="status">Assign to</label>
                                <div>
                                    <select id="status" v-model="form.user_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                        name="status">
                                        <option :value="null">None</option>
                                        <template v-for="user in props.availableUsers" :key="`assigned-to-${user.id}`">
                                            <option :value="user.id">{{ user.name }}</option>
                                        </template>
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
