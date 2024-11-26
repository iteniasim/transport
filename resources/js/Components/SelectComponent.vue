<template>
    <select v-bind="selectAttributes" :data-select="selectConfig" v-model="selectedValue" class="hidden">
        <option :value="null">{{ placeholder }}</option>
        <option v-for="item in options" :key="item[valueKey]" :value="item[valueKey]">
            {{ item[labelKey] }}
        </option>
    </select>
</template>

<script setup>
import { computed, ref } from 'vue';

// Props received from parent component
const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: 'Select option...',
    },
    valueKey: {
        type: String,
        default: 'value',
    },
    labelKey: {
        type: String,
        default: 'label',
    },
    id: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        default: '',
    },
});

// Reactive state for the selected value
const selectedValue = ref(null);

// Flyon UI select configuration
const selectConfig = computed(() => (JSON.stringify({
    placeholder: props.placeholder,
    toggleTag: "<button type='button' aria-expanded='false'></button>",
    toggleClasses: 'advance-select-toggle',
    dropdownClasses: 'advance-select-menu',
    optionClasses: 'advance-select-option selected:active',
    optionTemplate:
        "<div class='flex justify-between items-center w-full'><span data-title></span><span class='icon-[tabler--check] flex-shrink-0 size-4 text-primary hidden selected:block'></span></div>",
    extraMarkup:
        "<span class='icon-[tabler--caret-up-down] flex-shrink-0 size-4 text-base-content/90 absolute top-1/2 end-3 -translate-y-1/2'></span>",
})));

// Compute the select element's attributes, including the conditional `id`
const selectAttributes = computed(() => {
    const attrs = {};
    if (props.id) {
        attrs.id = props.id;  // Only add id if provided
    }
    if (props.name) {
        attrs.name = props.name;  // Only add name if provided
    }
    return attrs;
});
</script>
