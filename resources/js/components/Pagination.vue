<script setup lang="ts">
import {Link} from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav class="flex items-center justify-center gap-2">
        <template v-for="(link, index) in links" :key="index">
            <!-- Render a disabled link if the URL is null -->
            <div v-if="link.url === null"
                 class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                <span v-html="link.label"></span>
            </div>

            <!-- Render active/inactive links -->
            <Link v-else
                  :class="[
                      'inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md transition duration-200 ease-out',
                      link.active ? 'bg-indigo-400 text-white hover:bg-indigo-500' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                      link.label.includes('Previous') || link.label.includes('Next') ? '' : 'w-9 h-9 p-0'
                  ]"
                  :href="link.url"
                  preserve-scroll
                  :aria-current="link.active ? 'page' : null">
                <span v-html="link.label"></span>
            </Link>
        </template>
    </nav>
</template>
