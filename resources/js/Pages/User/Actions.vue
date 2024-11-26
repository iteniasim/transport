<template>
    <div>
        <button type="button" class="btn btn-circle btn-text btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="edit-user-form-modal"
            data-overlay="#edit-user-form-modal" @click="$emit('selectedUser', props.user)">
            <span class="icon-[tabler--pencil]"></span>
        </button>
        <button class="btn btn-circle btn-text btn-sm" v-if="!props.user['deleted_at']" @click="deleteAction(props.user)">
            <span class="icon-[tabler--trash]"></span>
        </button>
        <button v-else class="btn btn-circle btn-text btn-sm" @click="restoreAction(props.user)">
            <span class="icon-[tabler--restore]"></span>
        </button>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emits = defineEmits(['selectedUser']);

const deleteAction = (user) => {
    if (confirm("Are you sure you want to delete " + user.name)) {
        router.delete(route("users.destroy", user.id));
    }
};

const restoreAction = (user) => {
    if (confirm("Are you sure you want to restore " + user.name)) {
        router.post(route("users.restore", user.id));
    }
};
</script>
