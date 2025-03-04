<script setup lang="ts">
import {useForm} from '@inertiajs/vue3';
import {watch, computed} from 'vue';
import ModalComponent from '@/components/ModalComponent.vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: true,
    },
    user: {
        type: Object,
    },
    roles: {
        type: Array,
        required: true,
    },
});

// Initialize form with useForm
const form = useForm({
    name: '',
    email: '',
    role: null,
});

// Watch for changes in props.user and update the form
watch(
    () => props.user,
    (newUser) => {
        if (newUser) {
            form.name = newUser.name || '';
            form.email = newUser.email || '';
            if (newUser.roles.length > 0) {
                form.role = newUser.roles?.[0]?.id || null;
            }
        }
    },
    { immediate: true }
);

// computed prop to manage the modal visibility
const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

// Form submission
const submitForm = () => {
    form.put(route('users.update', props.user.id));
    closeModal()
};

const emit = defineEmits(['update:modelValue']);

const closeModal = () => {
    emit('update:modelValue', false);

    form.reset();
}
</script>

<template>
    <ModalComponent v-model="isOpen">
        <div class="mt-2">
            <form @submit.prevent="submitForm">
                <div class="space-y-12">

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">User Edit</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">User information will be updated on save.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-5">
                            <div class="sm:col-span-5">
                                <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                                <div class="mt-2">
                                    <input type="text" name="name" id="name" autocomplete="given-name" v-model="form.name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                                </div>
                            </div>

                            <div class="sm:col-span-5">
                                <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" autocomplete="email" v-model="form.email"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                                </div>
                            </div>

                            <div class="sm:col-span-5">
                                <label for="role" class="block text-sm/6 font-medium text-gray-900">Role</label>
                                <div class="mt-2">
                                    <select id="role" name="role" autocomplete="role-name" v-model="form.role"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                                        <template v-if="!form.role">
                                            <option :value="null">Select role...</option>
                                        </template>
                                        <option v-for="role in props.roles" :key="`role-${role.id}`" :value="role.id">
                                            {{ role.name }}
                                        </option>
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
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                :disabled="form.processing" @click="submitForm">
                Save changes
            </button>
        </div>
    </ModalComponent>
</template>
