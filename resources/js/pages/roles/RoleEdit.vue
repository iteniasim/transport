<script setup lang="ts">
import ModalComponent from '@/components/ModalComponent.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import {Permission, Role} from "@/types";

const props = defineProps<{
    modelValue: boolean;
    role: Role;
    permissions: Permission[];
}>();


const emit = defineEmits(['update:modelValue']);

const form = useForm<{
    name: string;
    permissions: string[];
}>({
    name: '',
    permissions: [],
});


watch(
    () => props.role,
    (newRole) => {
        if (newRole) {
            form.name = newRole.name || '';
            form.permissions = newRole.permissions?.map(p => p.name) || [];
        }
    },
    { immediate: true }
);

const submitForm = () => {
    form.put(route('roles.update', { id: props.role.id }), {
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    emit('update:modelValue', false);
    form.reset();
};

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
                        <h2 class="text-base/7 font-semibold text-gray-900">Edit Role</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">Update the role details below.</p>

                        <div class="mt-5 space-y-5">
                            <!-- Role Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                            </div>

                            <!-- Role Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                                <div class="mt-2 space-y-2 max-h-60 overflow-y-auto border rounded p-3">
                                    <div v-for="permission in permissions" :key="permission.name" class="flex items-center">
                                        <input
                                            type="checkbox"
                                            :value="permission.name"
                                            v-model="form.permissions"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        />
                                        <label class="ml-2 block text-sm text-gray-700">{{ permission.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 flex gap-4 justify-end">
            <button
                type="button"
                @click="closeModal"
                class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none"
            >
                Close
            </button>
            <button
                :disabled="form.processing"
                @click="submitForm"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none"
            >
                Save changes
            </button>
        </div>
    </ModalComponent>
</template>
