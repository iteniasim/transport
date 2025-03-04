<template>
    <span :class="colors[color]">
        {{ content }}
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

// Define props
defineProps({
    content: {
        type: String,
        required: true,
    },
    color: {
        type: String,
        default: 'dark',
    },
});

// Define the colors object inside the component
const colors = {
    dark: 'bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300',
    blue: 'bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300',
    red: 'bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300',
    green: 'bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300',
    yellow: 'bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300',
    indigo: 'bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300',
    purple: 'bg-purple-100 text-purple-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300',
};

const computedColorClass = computed(() => {
    return colors[props.color];
});
</script>
