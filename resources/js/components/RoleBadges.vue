<template>
    <span v-for="roleName in props.user.roles.map((role) => role.name)" :key="roleName + '-' + user.id"
        class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
        {{ roleName }}
    </span>
</template>

<script setup lang="ts">
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>
