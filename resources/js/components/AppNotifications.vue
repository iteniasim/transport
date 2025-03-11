<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton>
                <BellDotIcon v-if="hasNotifications" class="-mr-1 size-5 text-gray-400" aria-hidden="true" />
                <BellIcon v-else class="-mr-1 size-5 text-gray-400" aria-hidden="true" />
            </MenuButton>
        </div>

        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                <div class="py-1">
                    <MenuItem v-for="notification in notifications"  :key="notification.title" v-slot="{ active }">
                        <div :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            {{notification.data.message}}
                        </div>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, BellDotIcon } from 'lucide-vue-next';

import { usePage } from '@inertiajs/vue3';
import {computed} from "vue";

const page = usePage();
const notifications = page.props.auth.user.notifications

const hasNotifications = computed(() => notifications.length > 0)
</script>
