<template>
    <div class="flex justify-end">
        <button aria-label="Edit Role" type="button" @click="emit('edit-role', role)"
                class="bg-gray-50 hover:bg-gray-200 text-gray-500 font-bold py-2 px-2 rounded-full inline-flex items-center" >
            <Pencil />
        </button>
    </div>
    <div class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
        {{ role.name }}
    </div>
</template>
<script setup lang="ts">

// import { hasPermission } from '@/../composables/hasPermission.js';
import { Pencil } from 'lucide-vue-next';

const emit = defineEmits(['edit-role']);

defineProps({
    role: {
        type: Object,
        required: true,
    },
});
</script>
