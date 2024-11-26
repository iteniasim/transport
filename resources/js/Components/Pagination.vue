<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav class="flex items-center justify-center gap-x-1">
        <template v-for="(link, index) in links" :key="index">
            <!-- Render a disabled link if the URL is null -->
            <div v-if="link.url === null" class="btn btn-soft text-gray-400 cursor-not-allowed" v-html="link.label"></div>

            <!-- Render active/inactive links -->
            <Link v-else :href="link.url" :class="[
                'btn btn-soft',
                link.active ? 'aria-[current=\'page\']:text-bg-soft-primary' : '',
                link.label.includes('Previous') || link.label.includes('Next') ? '' : 'btn-square'
            ]" preserve-scroll :aria-current="link.active ? 'page' : null">
            <span v-html="link.label"></span>
            </Link>
        </template>
    </nav>
</template>
